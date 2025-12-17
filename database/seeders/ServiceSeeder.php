<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Website', 'description' => 'Website Development Services', 'display_order' => 1],
            ['name' => 'Software', 'description' => 'Software Development Services', 'display_order' => 2],
            ['name' => 'CRM', 'description' => 'CRM System Services', 'display_order' => 3],
            ['name' => 'Marketing', 'description' => 'Digital Marketing Services', 'display_order' => 4],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
}
