<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        if ($products->isEmpty()) return;

        $p1 = $products->firstWhere('name', 'T900 Ultra Smartwatch 2.0') ?? $products->first();
        $p2 = $products->firstWhere('name', 'AirPods Pro 2nd Gen Clone with ANC') ?? $products->last();

        Order::updateOrCreate(
            ['order_number' => 'ORD-20261010-9101'],
            [
                'customer_name' => 'মো: আরিফুল ইসলাম',
                'customer_phone' => '01712345678',
                'customer_address' => 'বাসা ২৫, রোড ৪, ব্লক সি, ধানমন্ডি, ঢাকা',
                'product_id' => $p1->id,
                'product_name' => $p1->name,
                'product_price' => $p1->discount_price ?: $p1->price,
                'quantity' => 1,
                'total_amount' => $p1->discount_price ?: $p1->price,
                'status' => 'pending',
                'platform' => 'messenger',
            ]
        );

        Order::updateOrCreate(
            ['order_number' => 'ORD-20261010-9102'],
            [
                'customer_name' => 'তানজিলা রহমান',
                'customer_phone' => '01898765432',
                'customer_address' => 'বাড়ি ১০, লেন ৩, আগ্রাবাদ, চট্টগ্রাম',
                'product_id' => $p2->id,
                'product_name' => $p2->name,
                'product_price' => $p2->discount_price ?: $p2->price,
                'quantity' => 2,
                'total_amount' => ($p2->discount_price ?: $p2->price) * 2,
                'status' => 'processing',
                'platform' => 'messenger',
            ]
        );
    }
}
