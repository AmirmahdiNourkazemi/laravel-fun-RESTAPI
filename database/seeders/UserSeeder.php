<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'type' => 1,  // Type could represent different roles (e.g., customer, admin)
            'email' => '1234@example.com',
            'mobile' => '1234567',
            'national_code' => '1234567',
            'uuid' => Str::uuid(), // Generate unique UUID
            'is_admin' => false,
            // Hashed password
        ]);
    }
}
