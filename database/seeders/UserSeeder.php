<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
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

        // Sales Person 1 - Fixed commission
        User::create([
            'name' => 'Rahim Ahmed',
            'email' => 'rahim@crm.com',
            'phone' => '01711111111',
            'password' => Hash::make('password'),
            'role' => 'sales_person',
            'default_commission_rate' => 500.00,
            'commission_type' => 'fixed',
            'is_active' => true,
        ]);

        // Sales Person 2 - Percentage commission
        User::create([
            'name' => 'Karim Hassan',
            'email' => 'karim@crm.com',
            'phone' => '01722222222',
            'password' => Hash::make('password'),
            'role' => 'sales_person',
            'default_commission_rate' => 10.00,
            'commission_type' => 'percentage',
            'is_active' => true,
        ]);

        // Sales Person 3 - Fixed commission
        User::create([
            'name' => 'Fatima Begum',
            'email' => 'fatima@crm.com',
            'phone' => '01733333333',
            'password' => Hash::make('password'),
            'role' => 'sales_person',
            'default_commission_rate' => 750.00,
            'commission_type' => 'fixed',
            'is_active' => true,
        ]);
    }
}
