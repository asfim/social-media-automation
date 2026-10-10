<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'T900 Ultra Smartwatch 2.0',
                'category' => 'Electronics',
                'description' => 'HD Full Touch Display with Bluetooth Calling & Heart Rate Monitoring.',
                'features' => 'Bluetooth Calling, Heart Rate Monitor, Sleep Tracker, 3-5 Days Battery Life.',
                'price' => 1450.00,
                'discount_price' => 1250.00,
                'availability' => 'in_stock',
                'image' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?auto=format&fit=crop&w=600&q=80',
                'status' => true,
            ],
            [
                'name' => 'AirPods Pro 2nd Gen Clone with ANC',
                'category' => 'Electronics',
                'description' => 'Active Noise Cancellation Wireless Earbuds with High Bass.',
                'features' => 'Active Noise Cancellation, 6h Playback, Wireless Charging Case, HD Microphone.',
                'price' => 1890.00,
                'discount_price' => 1550.00,
                'availability' => 'in_stock',
                'image' => 'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?auto=format&fit=crop&w=600&q=80',
                'status' => true,
            ],
            [
                'name' => 'Pure Sundarban Mustard Honey (1kg)',
                'category' => 'Organic Foods',
                'description' => '100% Raw & Pure Sundarban Honey straight from bee farmers.',
                'features' => 'Natural Energy Booster, Pure Organic, Chemical Free, High Medicinal Value.',
                'price' => 950.00,
                'discount_price' => 850.00,
                'availability' => 'in_stock',
                'image' => 'https://images.unsplash.com/photo-1587049352847-4a222e784d38?auto=format&fit=crop&w=600&q=80',
                'status' => true,
            ],
            [
                'name' => 'Genuine Pure Leather Bifold Wallet',
                'category' => 'Fashion',
                'description' => 'Premium Quality Genuine Cow Leather Wallet for Men.',
                'features' => '100% Genuine Leather, RFID Blocking, Multi Card Slots, Elegant Gift Packaging.',
                'price' => 1200.00,
                'discount_price' => 990.00,
                'availability' => 'in_stock',
                'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?auto=format&fit=crop&w=600&q=80',
                'status' => true,
            ],
            [
                'name' => 'Premium Cotton Polo Shirt - Navy Blue',
                'category' => 'Fashion',
                'description' => '100% Comb Cotton 220 GSM Breathable Polo T-Shirt.',
                'features' => '220 GSM Combed Cotton, Color Fastness Guaranteed, Comfortable Regular Fit.',
                'price' => 750.00,
                'discount_price' => 650.00,
                'availability' => 'in_stock',
                'image' => 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?auto=format&fit=crop&w=600&q=80',
                'status' => true,
            ]
        ];

        // Also update any existing products without image
        Product::whereNull('image')->orWhere('image', '')->get()->each(function($p) {
            $p->image = 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80';
            $p->save();
        });

        foreach ($products as $prod) {
            Product::updateOrCreate(['name' => $prod['name']], $prod);
        }
    }
}
