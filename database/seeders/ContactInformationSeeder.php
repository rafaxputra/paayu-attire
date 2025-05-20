<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ContactInformation::create([
            'address' => 'Jl. Puspowarno Ds. Tales Dsn. Cakruk Kec. Ngadiluwih Kab. Kediri',
            'phone' => '+62851 8300 4324‬',
            'instagram' => '@paayuattire',
            'email' => 'paayuattire@gmail.com',
            'tiktok' => '@paayuattire',
        ]);
    }
}
