<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Omolola Agosu',
            'email' => 'agosuomolola@gmail.com',
            'password' => Hash::make('Olola@gmail.com'), // secure password
            'role' => 'admin',
        ]);

        // Staff user
        User::create([
            'name' => 'Tunde Salawu',
            'email' => 'salawubabatunde69@gmail.com',
            'password' => Hash::make('stunde123'),
            'role' => 'staff',
        ]);

        // Regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);
    }
}
