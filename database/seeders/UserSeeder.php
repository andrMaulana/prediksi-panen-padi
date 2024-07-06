<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            'name' => 'inggit',
            'email' => 'inggit@admin.com',
            'password' => Hash::make('password'),
        ]);
    }
}
