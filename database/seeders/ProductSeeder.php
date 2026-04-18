<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create categories
        $electronics = Category::updateOrCreate(
            ['slug' => 'electronics'],
            ['name' => 'Electronics', 'description' => 'Electronic devices and gadgets']
        );

        $fashion = Category::updateOrCreate(
            ['slug' => 'fashion'],
            ['name' => 'Fashion', 'description' => 'Clothing and accessories']
        );

        $home = Category::updateOrCreate(
            ['slug' => 'home-garden'],
            ['name' => 'Home & Garden', 'description' => 'Home and garden products']
        );

        $sports = Category::updateOrCreate(
            ['slug' => 'sports'],
            ['name' => 'Sports', 'description' => 'Sports and outdoor equipment']
        );

        // Array of products with their details
        $products = [
            [
                'name' => 'Wireless Headphones Pro',
                'image' => 'img/product-1.png',
                'price' => 129.99,
                'original_price' => 179.99,
                'description' => 'Premium wireless headphones with noise cancellation, 30-hour battery life, and premium sound quality.',
                'category' => $electronics,
                'quantity' => 45,
                'is_featured' => true,
            ],
            [
                'name' => 'Smart Watch Ultra',
                'image' => 'img/product-2.png',
                'price' => 299.99,
                'original_price' => 399.99,
                'description' => 'Advanced smartwatch with fitness tracking, heart rate monitor, GPS, and 7-day battery life.',
                'category' => $electronics,
                'quantity' => 32,
                'is_featured' => true,
            ],
            [
                'name' => '4K USB Camera',
                'image' => 'img/product-3.png',
                'price' => 89.99,
                'original_price' => 119.99,
                'description' => 'High-resolution 4K USB camera perfect for streaming and video conferencing with auto-focus.',
                'category' => $electronics,
                'quantity' => 28,
                'is_featured' => false,
            ],
            [
                'name' => 'Premium Cotton T-Shirt',
                'image' => 'img/product-4.png',
                'price' => 29.99,
                'original_price' => 49.99,
                'description' => '100% organic cotton t-shirt with comfortable fit, available in multiple colors.',
                'category' => $fashion,
                'quantity' => 150,
                'is_featured' => false,
            ],
            [
                'name' => 'Denim Jacket Classic',
                'image' => 'img/product-5.png',
                'price' => 79.99,
                'original_price' => 129.99,
                'description' => 'Timeless denim jacket with modern cut, perfect for any occasion.',
                'category' => $fashion,
                'quantity' => 85,
                'is_featured' => false,
            ],
            [
                'name' => 'Casual Sports Shoes',
                'image' => 'img/product-6.png',
                'price' => 99.99,
                'original_price' => 149.99,
                'description' => 'Lightweight and comfortable sports shoes with cushioned sole for all-day wear.',
                'category' => $fashion,
                'quantity' => 120,
                'is_featured' => true,
            ],
            [
                'name' => 'Modern Table Lamp',
                'image' => 'img/product-7.png',
                'price' => 49.99,
                'original_price' => 79.99,
                'description' => 'Elegant LED table lamp with touch control and adjustable brightness.',
                'category' => $home,
                'quantity' => 60,
                'is_featured' => false,
            ],
            [
                'name' => 'Stainless Steel Cookware Set',
                'image' => 'img/product-8.png',
                'price' => 159.99,
                'original_price' => 229.99,
                'description' => 'Professional grade 10-piece stainless steel cookware set for your kitchen.',
                'category' => $home,
                'quantity' => 35,
                'is_featured' => false,
            ],
            [
                'name' => 'Cozy Bed Pillow Set',
                'image' => 'img/product-9.png',
                'price' => 79.99,
                'original_price' => 119.99,
                'description' => 'Set of 2 memory foam pillows with hypoallergenic cover for better sleep.',
                'category' => $home,
                'quantity' => 95,
                'is_featured' => false,
            ],
            [
                'name' => 'Professional Yoga Mat',
                'image' => 'img/product-10.png',
                'price' => 59.99,
                'original_price' => 89.99,
                'description' => 'Non-slip 6mm yoga mat with carrying strap, eco-friendly material.',
                'category' => $sports,
                'quantity' => 110,
                'is_featured' => false,
            ],
            [
                'name' => 'Mountain Bike Helmet',
                'image' => 'img/product-11.png',
                'price' => 119.99,
                'original_price' => 169.99,
                'description' => 'Safety certified mountain bike helmet with superior ventilation and adjustable fit.',
                'category' => $sports,
                'quantity' => 55,
                'is_featured' => false,
            ],
            [
                'name' => 'Portable Water Bottle',
                'image' => 'img/product-12.png',
                'price' => 34.99,
                'original_price' => 59.99,
                'description' => '1L insulated water bottle keeps drinks hot or cold for 24 hours.',
                'category' => $sports,
                'quantity' => 200,
                'is_featured' => false,
            ],
            [
                'name' => 'Gaming Mouse RGB',
                'image' => 'img/product-13.png',
                'price' => 69.99,
                'original_price' => 99.99,
                'description' => 'High precision gaming mouse with customizable RGB lighting and ergonomic design.',
                'category' => $electronics,
                'quantity' => 75,
                'is_featured' => false,
            ],
            [
                'name' => 'Mechanical Keyboard Pro',
                'image' => 'img/product-14.png',
                'price' => 149.99,
                'original_price' => 199.99,
                'description' => 'Premium mechanical keyboard with Cherry MX switches and RGB backlighting.',
                'category' => $electronics,
                'quantity' => 42,
                'is_featured' => false,
            ],
            [
                'name' => 'Portable SSD 1TB',
                'image' => 'img/product-15.png',
                'price' => 129.99,
                'original_price' => 179.99,
                'description' => 'Ultra-fast portable solid state drive with 1TB storage and USB-C connection.',
                'category' => $electronics,
                'quantity' => 88,
                'is_featured' => false,
            ],
            [
                'name' => 'Designer Sunglasses',
                'image' => 'img/product-16.png',
                'price' => 159.99,
                'original_price' => 249.99,
                'description' => 'Stylish UV-protected sunglasses with polarized lenses and premium frame.',
                'category' => $fashion,
                'quantity' => 70,
                'is_featured' => true,
            ],
            [
                'name' => 'Leather Crossbody Bag',
                'image' => 'img/product-17.png',
                'price' => 119.99,
                'original_price' => 189.99,
                'description' => 'Premium genuine leather crossbody bag with adjustable strap and multiple compartments.',
                'category' => $fashion,
                'quantity' => 48,
                'is_featured' => false,
            ],
            [
                'name' => 'Stainless Steel Water Jug',
                'image' => 'img/product-18.png',
                'price' => 44.99,
                'original_price' => 74.99,
                'description' => 'Large capacity stainless steel jug perfect for kitchen or outdoor use.',
                'category' => $home,
                'quantity' => 130,
                'is_featured' => false,
            ],
        ];

        // Create products
        foreach ($products as $productData) {
            $category = $productData['category'];
            unset($productData['category']);

            Product::updateOrCreate(
                ['slug' => Str::slug($productData['name'])],
                [
                    ...$productData,
                    'category_id' => $category->id,
                ]
            );
        }
    }
}
