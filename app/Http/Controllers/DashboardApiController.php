<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use App\Models\Faq;
use App\Models\Product;
use App\Services\AI\AIService;

class DashboardApiController extends Controller
{
    /**
     * Save Business Settings from /settings/business UI
     */
    public function saveBusinessSettings(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string',
            'industry' => 'nullable|string',
            'description' => 'nullable|string',
            'ai_name' => 'nullable|string',
            'language' => 'nullable|string',
            'tone' => 'nullable|string'
        ]);

        $setting = BusinessSetting::first() ?? new BusinessSetting();
        $setting->fill($validated);
        $setting->save();

        return response()->json(['success' => true, 'message' => 'Business settings saved successfully']);
    }

    /**
     * Add new FAQ from /ai/faq UI
     */
    public function addFaq(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'category' => 'nullable|string'
        ]);

        $faq = Faq::create($validated);

        return response()->json(['success' => true, 'data' => $faq]);
    }

    public function addKnowledge(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'category' => 'nullable|string',
            'content' => 'required|string',
        ]);

        $validated['category'] = $validated['category'] ?? 'general';

        $knowledge = \App\Models\BusinessKnowledge::create($validated);

        return response()->json(['success' => true, 'data' => $knowledge]);
    }

    public function updateKnowledge(Request $request, \App\Models\BusinessKnowledge $knowledge)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $knowledge->update($validated);

        return response()->json(['success' => true, 'data' => $knowledge]);
    }

    public function deleteKnowledge(\App\Models\BusinessKnowledge $knowledge)
    {
        $knowledge->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Add a new Product/Service from /ai/products UI
     */
    public function addProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image'] = asset('storage/' . $path);
        }

        unset($validated['image_file']);
        $product = Product::create($validated);

        return response()->json(['success' => true, 'data' => $product]);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image'] = asset('storage/' . $path);
        }

        unset($validated['image_file']);
        $product->update($validated);

        return response()->json(['success' => true, 'data' => $product]);
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Handle Playground Chat (Invokes AIService for real intelligent human Bangla response)
     */
    public function simulateChat(Request $request)
    {
        $message = $request->input('message', '');
        
        if (trim($message) === '') {
            return response()->json([
                'reply' => 'অনুগ্রহ করে কিছু লিখুন!',
                'intent' => 'Empty',
                'confidence' => '100%'
            ]);
        }

        $aiService = app(AIService::class);
        $result = $aiService->generateResponse($message, 'web', 'message');

        return response()->json([
            'reply' => $result['reply'],
            'intent' => ucfirst($result['intent'] ?? 'General'),
            'confidence' => ($result['confidence'] ?? 90) . '%'
        ]);
    }
}
