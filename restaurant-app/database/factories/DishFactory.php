<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class DishFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->unique()->randomElement([
                'Carpaccio de Res', 'Bruschetta Clásica', 'Tartar de Atún',
                'Risotto a la Milanesa', 'Lomo Saltado', 'Pollo a la Brasa',
                'Paella Valenciana', 'Salmón Glaseado', 'Filete Mignon',
                'Pasta Carbonara', 'Lasagna Bolognesa', 'Pizza Margherita',
                'Tiramisú', 'Crème Brûlée', 'Cheesecake de Frambuesa',
                'Mousse de Chocolate', 'Helado Artesanal', 'Flan de Caramelo',
                'Limonada Natural', 'Sangría de la Casa', 'Margarita Clásica',
                'Café Espresso', 'Capuchino', 'Té de Hierbas',
            ]),
            'description' => fake()->optional()->sentence(10),
            'price' => fake()->randomFloat(2, 5, 35),
            'is_available' => fake()->boolean(85),
        ];
    }
}
