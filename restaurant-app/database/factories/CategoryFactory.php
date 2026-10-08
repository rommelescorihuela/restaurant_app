<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Entradas', 'Platos Fuertes', 'Postres', 'Bebidas',
            'Especialidades de la Casa', 'Mariscos', 'Pastas', 'Ensaladas',
            'Cocktails', 'Vinos', 'Cafés', 'Menú Infantil',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->optional()->sentence(),
            'is_active' => true,
        ];
    }
}
