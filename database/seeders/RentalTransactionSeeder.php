<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import DB facade

class RentalTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rental_transactions')->insert([
            [
                'id' => 2,
                'trx_id' => 'RENTAL-4CJ6HaZR',
                'product_id' => 7,
                'name' => 'Rafa',
                'phone_number' => '085',
                'started_at' => '2025-05-13',
                'ended_at' => '2025-05-14',
                
                
                'total_amount' => 57000.00,
                
                'payment_proof' => 'payment_proofs/VqlQMIEC9rjQrMAtSKOwm62Q9byhazPjnOJ3JCLz.png',
                'payment_method' => 'BCA',
                'status' => 'payment_validated',
                'created_at' => '2025-05-11 17:15:07',
                'updated_at' => '2025-05-11 20:19:35',
                'user_id' => NULL, // Set user_id to NULL as original user might not exist or for simplicity
            ],
            [
                'id' => 3,
                'trx_id' => 'RENTAL-V0s5cw52',
                'product_id' => 9,
                'name' => 'rifky',
                'phone_number' => '12345',
                'started_at' => '2025-05-13',
                'ended_at' => '2025-05-15',
                
                
                'total_amount' => 300000.00,
                
                'payment_proof' => 'payment_proofs/8BLSyYWqJlylTttBLQgW8WjK4Qc1K1bAHEYJ7vxX.png',
                'payment_method' => 'BRI',
                'status' => 'payment_validated',
                'created_at' => '2025-05-12 08:45:59',
                'updated_at' => '2025-05-12 08:57:28',
                'user_id' => NULL, // Set user_id to NULL
            ],
        ]);
    }
}
