<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // User::create([
        //     'name' => 'Fuad Grimaldi',
        //     'email' => 'fuad@gmail.com',
        //     'password' => Hash::make('12345678'), // Pastikan password di-hash
        //     'username' => 'fuad',
        //     'profile_picture' => null,
        //     'ktp' => null,
        //     'verified' => true,
        // ]);
        // User::create([
        //     'name' => 'Abelz',
        //     'email' => 'bel@gmail.com',
        //     'password' => Hash::make('12345678'), // Pastikan password di-hash
        //     'username' => 'bel',
        //     'profile_picture' => null,
        //     'ktp' => null,
        //     'verified' => true,
        // ]);
    }
}
