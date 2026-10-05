<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use App\Models\Faq;
use App\Models\Product;

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

    /**
     * Add a new Product/Service from /ai/products UI
     */
    public function addProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'category' => 'nullable|string'
        ]);

        $product = Product::create($validated);

        return response()->json(['success' => true, 'data' => $product]);
    }

    /**
     * Handle Playground Chat (Simulates AI response via backend)
     */
    public function simulateChat(Request $request)
    {
        $message = $request->input('message');
        
        // This is where we would normally call $this->aiService->generateResponse($message)
        // For now, we simulate intelligent backend logic:
        
        $textLower = strtolower($message);
        $response = "I am the Backend AI Service! I received your message: '{$message}'. Currently waiting for an OpenAI API Key to be configured in settings.";
        $intent = "General";
        $confidence = "80%";
        
        if (str_contains($textLower, 'price') || str_contains($textLower, 'cost') || str_contains($textLower, 'taka')) {
            $response = "As per the database, eCommerce websites start at 20,000 BDT. Shall I create a lead profile for you?";
            $intent = "Pricing Inquiry";
            $confidence = "98%";
        } elseif (str_contains($textLower, 'location') || str_contains($textLower, 'where') || str_contains($textLower, 'address')) {
            $response = "Our office is at Banani, Road 11, Dhaka. Come visit us!";
            $intent = "Location Inquiry";
            $confidence = "95%";
        }

        // Simulate a slight API delay
        sleep(1);

        return response()->json([
            'reply' => $response,
            'intent' => $intent,
            'confidence' => $confidence
        ]);
    }
}
