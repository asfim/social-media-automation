<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Conversation;
use App\Models\Lead;
use App\Services\AI\AIService;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display the Admin Orders Management Page.
     */
    public function index(Request $request)
    {
        $query = Order::with(['product', 'conversation'])->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'total_sales' => Order::where('status', 'completed')->sum('total_amount'),
        ];

        return view('orders.index', compact('orders', 'stats'));
    }

    /**
     * Update order status (Admin action).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Order #{$order->order_number} status updated to " . ucfirst($order->status),
                'order' => $order
            ]);
        }

        return redirect()->back()->with('success', "Order #{$order->order_number} status updated to " . ucfirst($order->status));
    }

    /**
     * Display Customer Checkout Page.
     */
    public function checkout(Request $request, $productId = null)
    {
        $product = null;
        if ($productId) {
            $product = Product::find($productId);
        }

        if (!$product) {
            $product = Product::where('status', true)->first();
        }

        $products = Product::where('status', true)->get();
        $conversationId = $request->query('conversation_id');

        return view('checkout.index', compact('product', 'products', 'conversationId'));
    }

    /**
     * Store Customer Order from Checkout Form.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:191',
            'customer_phone' => 'required|string|max:50',
            'customer_address' => 'required|string|max:1000',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1|max:100',
            'conversation_id' => 'nullable|exists:conversations,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $quantity = $data['quantity'] ?? 1;
        $unitPrice = $product->discount_price && $product->discount_price > 0 ? $product->discount_price : $product->price;
        $totalAmount = $unitPrice * $quantity;

        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_address' => $data['customer_address'],
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_price' => $unitPrice,
            'quantity' => $quantity,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'platform' => 'messenger',
            'conversation_id' => $data['conversation_id'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        // Auto create/update Lead in CRM
        Lead::updateOrCreate(
            ['phone' => $data['customer_phone']],
            [
                'name' => $data['customer_name'],
                'address' => $data['customer_address'],
                'interested_service' => $product->name,
                'lead_status' => 'Hot',
                'lead_score' => 95,
                'platform' => 'messenger',
                'last_contacted_at' => now(),
            ]
        );

        // If placed from Messenger conversation, post confirmation message into conversation
        if (!empty($data['conversation_id'])) {
            $conversation = Conversation::find($data['conversation_id']);
            if ($conversation) {
                $confirmText = "🎉 ধন্যবাদ {$order->customer_name}!\n"
                    . "আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে।\n"
                    . "📦 অর্ডার আইডি: {$order->order_number}\n"
                    . "🛍️ পণ্য: {$order->product_name} (x{$order->quantity})\n"
                    . "💰 মোট মূল্য: ৳" . number_format($order->total_amount, 2) . "\n"
                    . "📍 ঠিকানা: {$order->customer_address}\n"
                    . "📞 ফোন: {$order->customer_phone}\n\n"
                    . "আমাদের প্রতিনিধি খুব শীঘ্রই ডেলিভারির বিষয়ে কল করে নিশ্চিত করবেন।";

                $conversation->messages()->create([
                    'external_message_id' => 'system_order_' . uniqid(),
                    'message_text' => $confirmText,
                    'sender_type' => 'ai',
                    'is_read' => true,
                ]);

                $conversation->update(['last_message_at' => now()]);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'আপনার অর্ডারটি সফলভাবে সম্পন্ন হয়েছে!',
                'order' => $order,
                'redirect_url' => route('checkout', ['product_id' => $product->id]) . '?success=1&order=' . $order->order_number
            ]);
        }

        return redirect()->route('checkout', ['product_id' => $product->id])->with('order_success', $order);
    }

    /**
     * Simulate customer message in Messenger (for testing AI human replies & product cards).
     */
    public function simulateCustomerMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $conversation = Conversation::findOrFail($id);

        // Add Customer message
        $custMsg = $conversation->messages()->create([
            'external_message_id' => 'sim_cust_' . uniqid(),
            'message_text' => $request->message,
            'sender_type' => 'customer',
            'sender_id' => $conversation->customer_id,
            'is_read' => true,
        ]);

        $conversation->update(['last_message_at' => now()]);

        // Generate AI Human Reply if AI is active
        $aiReplyMessage = null;
        if ($conversation->ai_active) {
            $aiService = app(AIService::class);
            
            $history = $conversation->messages()->latest('id')->take(10)->get()->reverse()->values()
                ->slice(0, -1)
                ->map(fn ($m) => [
                    'role' => $m->sender_type === 'customer' ? 'user' : 'assistant',
                    'content' => $m->message_text,
                ])->all();

            $result = $aiService->generateResponse($request->message, $conversation->platform, 'message', $history, $conversation->account);

            $aiReplyMessage = $conversation->messages()->create([
                'external_message_id' => 'sim_ai_' . uniqid(),
                'message_text' => $result['reply'],
                'sender_type' => 'ai',
                'ai_confidence' => $result['confidence'] ?? 95,
                'is_read' => true,
            ]);

            $conversation->update(['last_message_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'customer_message' => [
                'text' => $custMsg->message_text,
                'time' => $custMsg->created_at->format('h:i A'),
            ],
            'ai_message' => $aiReplyMessage ? [
                'text' => $aiReplyMessage->message_text,
                'time' => $aiReplyMessage->created_at->format('h:i A'),
            ] : null,
        ]);
    }
}
