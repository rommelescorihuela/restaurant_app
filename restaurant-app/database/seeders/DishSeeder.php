<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Dish;
use Illuminate\Database\Seeder;

class DishSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');

        $dishes = [
            'Entradas' => [
                ['name' => 'Carpaccio de Res', 'price' => 12.90, 'description' => 'Finas láminas de res con alcaparras, parmesano y rúcula.'],
                ['name' => 'Bruschetta Clásica', 'price' => 8.50, 'description' => 'Pan artesanal con tomate, albahaca y aceite de oliva.'],
                ['name' => 'Tartar de Atún', 'price' => 15.00, 'description' => 'Atún fresco marinado con aguacate y salsa de soya.'],
                ['name' => 'Croquetas de Jamón', 'price' => 9.50, 'description' => 'Crema de jamón serrano con bechamel y crujiente empanizado.'],
                ['name' => 'Ceviche de Camarón', 'price' => 11.00, 'description' => 'Camarón fresco cocido en limón con cebolla y cilantro.'],
            ],
            'Platos Fuertes' => [
                ['name' => 'Risotto a la Milanesa', 'price' => 18.50, 'description' => 'Risotto cremoso con ossobuco y gremolata.'],
                ['name' => 'Lomo Saltado', 'price' => 16.00, 'description' => 'Tiras de lomo salteadas con cebolla, tomate y papas fritas.'],
                ['name' => 'Salmón Glaseado', 'price' => 22.00, 'description' => 'Salmón con glaseado de miel y mostaza, acompañado de verduras.'],
                ['name' => 'Filete Mignon', 'price' => 28.00, 'description' => 'Filete de res con salsa de champiñones y puré de papas.'],
                ['name' => 'Paella Valenciana', 'price' => 24.50, 'description' => 'Arroz con mariscos, pollo y azafrán.'],
                ['name' => 'Pasta Carbonara', 'price' => 15.50, 'description' => 'Spaghetti con huevo, panceta y parmesano.'],
                ['name' => 'Pollo a la Brasa', 'price' => 14.00, 'description' => 'Pollo marinado y asado al carbón con papas rústicas.'],
            ],
            'Postres' => [
                ['name' => 'Tiramisú', 'price' => 9.90, 'description' => 'Clásico italiano con mascarpone, café y cacao.'],
                ['name' => 'Crème Brûlée', 'price' => 8.50, 'description' => 'Crema de vainilla con capa de caramelo crujiente.'],
                ['name' => 'Cheesecake de Frambuesa', 'price' => 9.00, 'description' => 'Queso crema sobre base de galleta con coulis de frambuesa.'],
                ['name' => 'Mousse de Chocolate', 'price' => 8.00, 'description' => 'Chocolate belga semi-amargo con crema batida.'],
                ['name' => 'Flan de Caramelo', 'price' => 7.50, 'description' => 'Flan cremoso con caramelo líquido.'],
            ],
            'Bebidas' => [
                ['name' => 'Limonada Natural', 'price' => 4.50, 'description' => 'Limón fresco, hierbabuena y un toque de jengibre.'],
                ['name' => 'Sangría de la Casa', 'price' => 6.00, 'description' => 'Vino tinto con frutas de temporada.'],
                ['name' => 'Margarita Clásica', 'price' => 8.00, 'description' => 'Tequila, triple sec y limón fresco.'],
                ['name' => 'Café Espresso', 'price' => 3.00, 'description' => 'Espresso italiano de tueste medio.'],
                ['name' => 'Capuchino', 'price' => 4.00, 'description' => 'Espresso con leche vaporizada y espuma cremosa.'],
                ['name' => 'Té de Hierbas', 'price' => 3.50, 'description' => 'Selección de tés orgánicos.'],
            ],
            'Ensaladas' => [
                ['name' => 'Ensalada del Chef', 'price' => 11.00, 'description' => 'Mix de verdes con vinagreta balsámica, nueces y queso de cabra.'],
                ['name' => 'Ensalada César', 'price' => 10.00, 'description' => 'Lechuga romana, crutones, parmesano y aderezo césar.'],
                ['name' => 'Ensalada Griega', 'price' => 11.50, 'description' => 'Tomate, pepino, aceitunas, cebolla y queso feta.'],
            ],
        ];

        foreach ($dishes as $categoryName => $items) {
            $categoryId = $categories[$categoryName] ?? null;
            if (!$categoryId) continue;

            foreach ($items as $item) {
                Dish::create([
                    'category_id' => $categoryId,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'is_available' => true,
                ]);
            }
        }
    }
}
