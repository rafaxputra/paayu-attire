<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample products and sizes
        $product1 = \App\Models\Product::create([
            'name' => 'Kebaya Modern A',
            'slug' => 'kebaya-modern-a',
            'thumbnail' => 'path/to/kebaya_modern_a_thumbnail.jpg', // Replace with actual path
            'about' => 'Beautiful modern kebaya.',
            'price' => 150000, // Price per day
        ]);
        $product1->productSizes()->createMany([
            ['size' => 'S', 'stock' => 5],
            ['size' => 'M', 'stock' => 10],
            ['size' => 'L', 'stock' => 3],
        ]);

        $product2 = \App\Models\Product::create([
            'name' => 'Kebaya Brokat B',
            'slug' => 'kebaya-brokat-b',
            'thumbnail' => 'path/to/kebaya_brokat_b_thumbnail.jpg', // Replace with actual path
            'about' => 'Elegant brokat kebaya.',
            'price' => 200000, // Price per day
        ]);
        $product2->productSizes()->createMany([
            ['size' => 'M', 'stock' => 7],
            ['size' => 'L', 'stock' => 5],
            ['size' => 'XL', 'stock' => 2],
        ]);

        // Add more sample products as needed
    }
}
