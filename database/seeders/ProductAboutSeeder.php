<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product; // Import the Product model

class ProductAboutSeeder extends Seeder
{
    public function run()
    {
        $productsData = [
            'kebaya-abu-floral-mewah' => [
                'about' => 'Kebaya dengan detail floral mewah.',
                'material' => "1. Bahan utama: Organza premium\n2. Lapisan dalam: Furing halus, adem, dan nyaman dipakai\n3. Detail: Bordir bunga dan benang metalik",
                'color' => 'Soft Grey',
                'size_chart' => "Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan\nS : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm\nM : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm\nL : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm\nXL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",
            ],
            'kebaya-payet-burgundy-elegan' => [
                'about' => 'Kebaya elegan dengan payet berwarna burgundy.',
                'material' => "1. Bahan utama: Kain tile mewah berpayet penuh\n2. Lapisan dalam: Furing halus, nyaman di kulit, dan tidak menerawang",
                'color' => 'Burgundy',
                'size_chart' => "Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan\nS : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm\nM : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm\nL : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm\nXL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",
            ],
            'kebaya-elegan-cream' => [
                'about' => 'Kebaya cream elegan dengan motif klasik.',
                'material' => "1. Bahan utama: Brokat tile premium dengan motif klasik\n2. Lapisan dalam: Furing ringan dan adem, nyaman dikenakan",
                'color' => 'Soft Gold',
                'size_chart' => "Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan\nS : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm\nM : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm\nL : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm\nXL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",
            ],
            'kebaya-biru-pastel-floral' => [
                'about' => 'Kebaya biru pastel dengan motif floral.',
                'material' => "1. Bahan utama: Brokat premium bermotif bunga dengan lapisan ringan\n2. Furing dalam: Bahan halus dan adem, tidak menerawang",
                'color' => 'Biru Pastel',
                'size_chart' => "Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan\nS : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm\nM : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm\nL : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm\nXL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",
            ],
            'kebaya-biru-malam-floral-elegan' => [
                'about' => 'Kebaya biru malam elegan dengan motif floral.',
                'material' => "1. Bahan utama: Brokat premium bermotif bunga dengan lapisan ringan\n2. Furing dalam: Bahan halus dan adem, tidak menerawang",
                'color' => 'Navy (Biru Dongker)',
                'size_chart' => "Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan\nS : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm\nM : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm\nL : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm\nXL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",
            ],
            'kebaya-biru-gemerlap-mewah' => [
                'about' => 'Kebaya biru gemerlap mewah.',
                'material' => "1. Bahan utama: Brokat tile premium full payet warna emerald\n2. Lapisan dalam: Furing halus yang nyaman, tidak panas, dan anti menerawang",
                'color' => 'Emerald Blue',
                'size_chart' => "Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan\nS : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm\nM : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm\nL : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm\nXL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",
            ],
        ];

        foreach ($productsData as $slug => $data) {
            Product::where('slug', $slug)->update($data);
        }
    }
}
