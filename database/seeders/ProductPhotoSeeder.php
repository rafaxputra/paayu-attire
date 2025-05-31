<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import DB facade

class ProductPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_photos')->insert([
            [
                'id' => 1,
                'photo' => 'product_photos/01JTXDN7S0ZN9EX92Z455RW2WP.jpg',
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-05-10 15:40:11',
                'updated_at' => '2025-05-10 15:40:11',
            ],
            [
                'id' => 2,
                'photo' => 'product_photos/01JTXDN7S7KJDGVDRBAZGBQ7DV.jpg',
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-05-10 15:40:11',
                'updated_at' => '2025-05-10 15:40:11',
            ],
            [
                'id' => 3,
                'photo' => 'product_photos/01JV1QG6831F3ZKMYF3G70HT9Q.jpg',
                'product_id' => 8,
                'deleted_at' => NULL,
                'created_at' => '2025-05-12 07:49:10',
                'updated_at' => '2025-05-12 07:49:10',
            ],
            [
                'id' => 4,
                'photo' => 'product_photos/01JV1QG689BKW7VQEDPPXJXJ59.jpg',
                'product_id' => 8,
                'deleted_at' => NULL,
                'created_at' => '2025-05-12 07:49:10',
                'updated_at' => '2025-05-12 07:49:10',
            ],
            [
                'id' => 5,
                'photo' => 'product_photos/01JV1R7PNQEJZKHJ91M77CDB29.jpg',
                'product_id' => 9,
                'deleted_at' => NULL,
                'created_at' => '2025-05-12 08:02:00',
                'updated_at' => '2025-05-12 08:02:00',
            ],
            [
                'id' => 6,
                'photo' => 'product_photos/01JV1RB6YT7XMNSAET8T2P1R5S.jpg',
                'product_id' => 9,
                'deleted_at' => NULL,
                'created_at' => '2025-05-12 08:03:55',
                'updated_at' => '2025-05-12 08:03:55',
            ],
            [
                'id' => 7,
                'photo' => 'product_photos/01JV1SB9HZ4TDJCCY7JESST90Z.jpg',
                'product_id' => 10,
                'deleted_at' => NULL,
                'created_at' => '2025-05-12 08:21:26',
                'updated_at' => '2025-05-12 08:21:26',
            ],
            [
                'id' => 8,
                'photo' => 'product_photos/01JV1SJTTK3Y4C3JDCM7SG7JWV.jpg',
                'product_id' => 11,
                'deleted_at' => NULL,
                'created_at' => '2025-05-12 08:25:33',
                'updated_at' => '2025-05-12 08:25:33',
            ],
            [
                'id' => 9,
                'photo' => 'product_photos/01JV1T3XMAS4EGT2MNZGDJTP62.jpg',
                'product_id' => 12,
                'deleted_at' => NULL,
                'created_at' => '2025-05-12 08:34:53',
                'updated_at' => '2025-05-12 08:34:53',
            ],
            [
                'id' => 10,
                'photo' => 'product_photos/01JV1T3XMHPER39GZG496MC560.jpg',
                'product_id' => 12,
                'deleted_at' => NULL,
                'created_at' => '2025-05-12 08:34:53',
                'updated_at' => '2025-05-12 08:34:53',
            ],
        ]);
    }
}
