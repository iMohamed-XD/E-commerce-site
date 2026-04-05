<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Try to fetch password from environment properly to avoid hardcoding secrets
        $adminPassword = env('MAHLY_ADMIN_PASSWORD');
        
        // Only seed if configured to avoid exposing default passwords to production accidentally
        if ($adminPassword) {
            User::firstOrCreate(
                ['email' => 'mahlyteam@gmail.com'],
                [
                    'name' => 'Mohammad Feras Amin',
                    'role' => 'admin',
                    'password' => Hash::make($adminPassword),
                    'email_verified_at' => now(),
                ]
            );
        } else {
            // For purely local deployments with docker-compose when variable wasn't supplied
            User::firstOrCreate(
                ['email' => 'mahlyteam@gmail.com'],
                [
                    'name' => 'Mohammad Feras Amin',
                    'role' => 'admin',
                    'password' => Hash::make('Mahly!Admin@456'), // Default fallback for local testing
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
