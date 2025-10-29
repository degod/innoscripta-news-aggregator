<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'uuid' => Str::uuid()->toString(),
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'is_marketing' => false,
            ]);
        }

        User::factory(10)->create();
    }
}
