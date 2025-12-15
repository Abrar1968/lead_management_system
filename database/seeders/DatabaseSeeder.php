<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Only create admin user for fresh testing
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@crm.com',
            'phone' => '01700000000',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'default_commission_rate' => 0.00,
            'commission_type' => 'fixed',
            'is_active' => true,
        ]);
    }
}
