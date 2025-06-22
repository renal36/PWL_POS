<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
       
        User::create([
            'name' => 'Administrator',
            'username' => 'admin', 
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'), 
            'role' => 'admin', 
        ]);

       
    }
}