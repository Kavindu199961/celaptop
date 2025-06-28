<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@celaptop.com',
            'password' => Hash::make('CE1999.03.19'),
            'role' => 'admin',
            'is_active' => true,
        ]);

       
    }
}