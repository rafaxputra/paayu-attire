<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import DB facade

class CustomTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('custom_transactions')->insert([
            [
                'id' => 1,
                'trx_id' => 'CUSTOM-c6XIxVbg',
                'name' => 'RAfasih',
                'phone_number' => '899',
                'image_reference' => 'custom_kebaya_references/8AhdJLeZ4CYf1DtAyoZdLVYCSsDRwbuglu4SEjpO.png',
                'image_reference_2' => NULL, // Assuming these columns exist based on migrations
                'image_reference_3' => NULL, // Assuming these columns exist based on migrations
                'kebaya_preference' => 'yang hapik',
                'amount_to_buy' => 2,
                'date_needed' => '2025-05-22',
                'delivery_type' => 'pickup',
                'address' => NULL,
                'admin_price' => 680000.00,
                'admin_estimated_completion_date' => '2025-05-21',
                'status' => 'completed',
                'is_paid' => 0,
                'payment_proof' => 'custom_payment_proofs/xAVD20X8UZCttSQ1QcLmbwtaOpox3PdqKL9AmJfP.png',
                'payment_method' => 'BCA',
                'created_at' => '2025-05-11 19:31:06',
                'updated_at' => '2025-05-11 19:54:12',
                'user_id' => NULL, // Set user_id to NULL
            ],
            [
                'id' => 2,
                'trx_id' => 'CUSTOM-a3BYHPjy',
                'name' => 'elsya',
                'phone_number' => '087',
                'image_reference' => 'custom_kebaya_references/qgc6WyA40oCkm1nYqHWCwEyRAUaTLAk06EBCgfsH.jpg',
                'image_reference_2' => NULL, // Assuming these columns exist based on migrations
                'image_reference_3' => NULL, // Assuming these columns exist based on migrations
                'kebaya_preference' => 'bagus kaya foto',
                'amount_to_buy' => 2,
                'date_needed' => '2025-05-13',
                'delivery_type' => 'pickup',
                'address' => NULL,
                'admin_price' => 200000.00,
                'admin_estimated_completion_date' => '2025-05-30',
                'status' => 'in_progress',
                'is_paid' => 1,
                'payment_proof' => 'custom_payment_proofs/UhWIojmzpWnd4DT9RZKUfhcgqodNEOS4Df3XCmzO.png',
                'payment_method' => 'BRI',
                'created_at' => '2025-05-12 09:08:44',
                'updated_at' => '2025-05-12 09:33:35',
                'user_id' => NULL, // Set user_id to NULL
            ],
        ]);
    }
}
