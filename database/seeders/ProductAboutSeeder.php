<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAboutSeeder extends Seeder
{
    public function run()
    {
        $products = [
            'kebaya-abu-floral-mewah' => "<strong>Material/Bahan:</strong><br>1. Bahan utama: Organza premium<br>2. Lapisan dalam: Furing halus, adem, dan nyaman dipakai<br>3. Detail: Bordir bunga dan benang metalik<br><br><strong>Color:</strong><br>Soft Grey<br><br><strong>Size Chart:</strong><br>Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan<br>S : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm<br>M : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm<br>L : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm<br>XL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",

            'kebaya-payet-burgundy-elegan' => "<strong>Material/Bahan:</strong><br>1. Bahan utama: Kain tile mewah berpayet penuh<br>2. Lapisan dalam: Furing halus, nyaman di kulit, dan tidak menerawang<br><br><strong>Color:</strong><br>Burgundy<br><br><strong>Size Chart:</strong><br>Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan<br>S : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm<br>M : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm<br>L : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm<br>XL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",

            'kebaya-elegan-cream' => "<strong>Material/Bahan:</strong><br>1. Bahan utama: Brokat tile premium dengan motif klasik<br>2. Lapisan dalam: Furing ringan dan adem, nyaman dikenakan<br><br><strong>Color:</strong><br>Soft Gold<br><br><strong>Size Chart:</strong><br>Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan<br>S : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm<br>M : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm<br>L : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm<br>XL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",

            'kebaya-biru-pastel-floral' => "<strong>Material/Bahan:</strong><br>1. Bahan utama: Brokat premium bermotif bunga dengan lapisan ringan<br>2. Furing dalam: Bahan halus dan adem, tidak menerawang<br><br><strong>Color:</strong><br>Biru Pastel<br><br><strong>Size Chart:</strong><br>Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan<br>S : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm<br>M : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm<br>L : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm<br>XL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",

            'kebaya-biru-malam-floral-elegan' => "<strong>Material/Bahan:</strong><br>1. Bahan utama: Brokat premium bermotif bunga dengan lapisan ringan<br>2. Furing dalam: Bahan halus dan adem, tidak menerawang<br><br><strong>Color:</strong><br>Navy (Biru Dongker)<br><br><strong>Size Chart:</strong><br>Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan<br>S : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm<br>M : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm<br>L : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm<br>XL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",

            'kebaya-biru-gemerlap-mewah' => "<strong>Material/Bahan:</strong><br>1. Bahan utama: Brokat tile premium full payet warna emerald<br>2. Lapisan dalam: Furing halus yang nyaman, tidak panas, dan anti menerawang<br><br><strong>Color:</strong><br>Emerald Blue<br><br><strong>Size Chart:</strong><br>Lebar Bahu Belakang | Lingkar Panggul | Lingkar Pinggul | Lingkar Dada | Kerung Lengan<br>S : 36 cm | 88 cm | 66 cm | 86 cm | 42 cm<br>M : 38 cm | 96 cm | 72 cm | 92 cm | 44 cm<br>L : 39 cm | 108 cm | 78 cm | 98 cm | 48 cm<br>XL : 40 cm | 112 cm | 84 cm | 104 cm | 50 cm",
        ];

        foreach ($products as $slug => $about) {
            DB::table('products')->where('slug', $slug)->update(['about' => $about]);
        }
    }
}
