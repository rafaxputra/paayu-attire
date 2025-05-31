<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import DB facade

class ProductSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_sizes')->insert([
            [
                'id' => 19,
                'product_id' => 7,
                'size' => 'M',
                'stock' => 2,
                'created_at' => '2025-05-10 15:40:11',
                'updated_at' => '2025-05-11 17:15:07',
            ],
            [
                'id' => 20,
                'product_id' => 8,
                'size' => 'L',
                'stock' => 1,
                'created_at' => '2025-05-12 07:49:10',
                'updated_at' => '2025-05-12 07:49:10',
            ],
            [
                'id' => 21,
                'product_id' => 8,
                'size' => 'XL',
                'stock' => 1,
                'created_at' => '2025-05-12 07:49:10',
                'updated_at' => '2025-05-12 07:49:10',
            ],
            [
                'id' => 22,
                'product_id' => 8,
                'size' => 'M',
                'stock' => 1,
                'created_at' => '2025-05-12 07:49:10',
                'updated_at' => '2025-05-12 07:49:10',
            ],
            [
                'id' => 23,
                'product_id' => 9,
                'size' => 'L',
                'stock' => 1,
                'created_at' => '2025-05-12 08:02:00',
                'updated_at' => '2025-05-12 08:45:59',
            ],
            [
                'id' => 24,
                'product_id' => 10,
                'size' => 'M',
                'stock' => 5,
                'created_at' => '2025-05-12 08:21:26',
                'updated_at' => '2025-05-12 08:21:26',
            ],
            [
                'id' => 25,
                'product_id' => 10,
                'size' => 'L',
                'stock' => 1,
                'created_at' => '2025-05-12 08:21:26',
                'updated_at' => '2025-05-12 08:21:26',
            ],
            [
                'id' => 26,
                'product_id' => 11,
                'size' => 'M',
                'stock' => 2,
                'created_at' => '2025-05-12 08:25:33',
                'updated_at' => '2025-05-12 08:25:33',
            ],
            [
                'id' => 27,
                'product_id' => 12,
                'size' => 'M',
                'stock' => 2,
                'created_at' => '2025-05-12 08:34:53',
                'updated_at' => '2025-05-12 08:34:53',
            ],
            [
                'id' => 28,
                'product_id' => 12,
                'size' => 'L',
                'stock' => 2,
                'created_at' => '2025-05-12 08:34:53',
                'updated_at' => '2025-05-12 08:34:53',
            ],
        ]);
    }
}
