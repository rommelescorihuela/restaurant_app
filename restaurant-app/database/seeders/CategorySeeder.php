<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Entradas', 'description' => 'El inicio perfecto para tu experiencia culinaria.'],
            ['name' => 'Platos Fuertes', 'description' => 'Nuestras especialidades que roban el protagonismo.'],
            ['name' => 'Postres', 'description' => 'El cierre dulce que toda comida merece.'],
            ['name' => 'Bebidas', 'description' => 'Refrescantes opciones para acompañar tu comida.'],
            ['name' => 'Ensaladas', 'description' => 'Opción ligera y saludable sin sacrificar sabor.'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}