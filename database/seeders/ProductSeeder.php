<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import DB facade

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'id' => 7,
                'name' => 'Kebaya Biru Gemerlap Mewah',
                'slug' => 'kebaya-biru-gemerlap-mewah',
                'thumbnail' => '01JTXDN7RMP8ZCWVE2GS03R0K5.jpg',
                'about' => 'Kebaya biru gemerlap mewah.',
                'material' => "1. Bahan utama: Brokat tile premium full payet warna emerald\n2. Lapisan dalam: Furing halus yang nyaman, tidak panas, dan anti menerawang",
                'color' => 'Emerald Blue',
                'size_chart' => "Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan\nS : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm\nM : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm\nL : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm\nXL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",
                'price' => 22500000,
                'deleted_at' => NULL,
                'created_at' => '2025-05-10 15:40:11',
                'updated_at' => '2025-05-12 07:58:40',
            ],
            [
                'id' => 8,
                'name' => 'Kebaya Payet Burgundy Elegan',
                'slug' => 'kebaya-payet-burgundy-elegan',
                'thumbnail' => '01JV1QG67TQCE9WH7545BVYK0A.jpg',
                'about' => 'Kebaya elegan dengan payet berwarna burgundy.',
                'material' => "1. Bahan utama: Kain tile mewah berpayet penuh\n2. Lapisan dalam: Furing halus, nyaman di kulit, dan tidak menerawang",
                'color' => 'Burgundy',
                'size_chart' => "Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan\nS : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm\nM : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm\nL : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm\nXL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",
                'price' => 55000000,
                'deleted_at' => NULL,
                'created_at' => '2025-05-12 07:49:10',
                'updated_at' => '2025-05-12 08:41:02',
            ],
            [
                'id' => 9,
                'name' => 'Kebaya Elegan Cream',
                'slug' => 'kebaya-elegan-cream',
                'thumbnail' => '01JV1R7PNAHCMZSGSXZ2MQFE1Q.jpg',
                'about' => 'Kebaya cream elegan dengan motif klasik.',
                'material' => "1. Bahan utama: Brokat tile premium dengan motif klasik\n2. Lapisan dalam: Furing ringan dan adem, nyaman dikenakan",
                'color' => 'Soft Gold',
                'size_chart' => "Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan\nS : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm\nM : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm\nL : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm\nXL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",
                'price' => 15000000,
                'deleted_at' => NULL,
                'created_at' => '2025-05-12 08:02:00',
                'updated_at' => '2025-05-12 08:02:00',
            ],
            [
                'id' => 10,
                'name' => 'Kebaya Biru Malam Floral Elegan',
                'slug' => 'kebaya-biru-malam-floral-elegan',
                'thumbnail' => '01JV1SB9HQZ1BC2TFB7EWK7ACC.jpg',
                'about' => 'Kebaya biru malam elegan dengan motif floral.',
                'material' => "1. Bahan utama: Brokat premium bermotif bunga dengan lapisan ringan\n2. Furing dalam: Bahan halus dan adem, tidak menerawang",
                'color' => 'Navy (Biru Dongker)',
                'size_chart' => "Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan\nS : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm\nM : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm\nL : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm\nXL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",
                'price' => 6500000,
                'deleted_at' => NULL,
                'created_at' => '2025-05-12 08:21:26',
                'updated_at' => '2025-05-12 08:38:30',
            ],
            [
                'id' => 11,
                'name' => 'Kebaya Biru Pastel Floral',
                'slug' => 'kebaya-biru-pastel-floral',
                'thumbnail' => '01JV1SJTTC2SGANY4DB1MQQEYV.jpg',
                'about' => 'Kebaya biru pastel dengan motif floral.',
                'material' => "1. Bahan utama: Brokat premium bermotif bunga dengan lapisan ringan\n2. Furing dalam: Bahan halus dan adem, tidak menerawang",
                'color' => 'Biru Pastel',
                'size_chart' => "Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan\nS : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm\nM : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm\nL : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm\nXL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",
                'price' => 20000000,
                'deleted_at' => NULL,
                'created_at' => '2025-05-12 08:25:33',
                'updated_at' => '2025-05-12 08:25:33',
            ],
            [
                'id' => 12,
                'name' => 'Kebaya Abu Floral Mewah',
                'slug' => 'kebaya-abu-floral-mewah',
                'thumbnail' => '01JV1T3XM2CQNHPH6VVF5AGN12.jpg',
                'about' => 'Kebaya dengan detail floral mewah.',
                'material' => "1. Bahan utama: Organza premium\n2. Lapisan dalam: Furing halus, adem, dan nyaman dipakai\n3. Detail: Bordir bunga dan benang metalik",
                'color' => 'Soft Grey',
                'size_chart' => "Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan\nS : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm\nM : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm\nL : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm\nXL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",
                'price' => 17500000,
                'deleted_at' => NULL,
                'created_at' => '2025-05-12 08:34:53',
                'updated_at' => '2025-05-12 08:34:53',
            ],
        ]);
    }
}
