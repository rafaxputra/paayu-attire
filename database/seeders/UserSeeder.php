<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Import Hash facade
use App\Models\User; // Import User model

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        User::create([
            'name' => 'Rafa',
            'email' => 'inituhrafa@Gmail.com',
            'password' => Hash::make('yarafa'),
            'role' => 'admin',
            'phone_number' => '1234567890',
        ]);

        // Create a customer user
        User::create([
            'name' => 'Elcan',
            'email' => 'elsa@gmail.com',
            'password' => Hash::make('yaelsa'),
            'role' => 'customer',
            'phone_number' => '0987654321',
        ]);
    }
}
