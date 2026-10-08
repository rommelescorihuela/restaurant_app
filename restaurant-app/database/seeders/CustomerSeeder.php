<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'María García', 'email' => 'maria@ejemplo.com', 'phone' => '+1 (555) 111-2233'],
            ['name' => 'Carlos López', 'email' => 'carlos@ejemplo.com', 'phone' => '+1 (555) 222-3344'],
            ['name' => 'Ana Martínez', 'email' => 'ana@ejemplo.com', 'phone' => '+1 (555) 333-4455'],
            ['name' => 'Roberto Sánchez', 'email' => 'roberto@ejemplo.com', 'phone' => '+1 (555) 444-5566'],
            ['name' => 'Laura Fernández', 'email' => 'laura@ejemplo.com', 'phone' => '+1 (555) 555-6677'],
            ['name' => 'Pedro Ramírez', 'email' => 'pedro@ejemplo.com', 'phone' => '+1 (555) 666-7788'],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['email' => $customer['email']],
                $customer
            );
        }
    }
}