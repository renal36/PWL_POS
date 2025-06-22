<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Penting: Import model User Anda
use Illuminate\Support\Facades\Hash; // Penting: Import Hash Facade

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
      
        User::create([
            'name' => 'Administrator',
            'username' => 'admin', // Username untuk login
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'), // Ganti dengan password Anda
            'role' => 'admin',
        ]);

    }
}