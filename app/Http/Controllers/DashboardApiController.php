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
}
