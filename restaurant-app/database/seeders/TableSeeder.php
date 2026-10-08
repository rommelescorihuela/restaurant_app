<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            ['number' => '1', 'capacity' => 2, 'location' => 'Interior'],
            ['number' => '2', 'capacity' => 2, 'location' => 'Interior'],
            ['number' => '3', 'capacity' => 4, 'location' => 'Interior'],
            ['number' => '4', 'capacity' => 4, 'location' => 'Interior'],
            ['number' => '5', 'capacity' => 6, 'location' => 'Interior'],
            ['number' => '6', 'capacity' => 4, 'location' => 'Terraza'],
            ['number' => '7', 'capacity' => 2, 'location' => 'Terraza'],
            ['number' => '8', 'capacity' => 4, 'location' => 'Terraza'],
            ['number' => '9', 'capacity' => 8, 'location' => 'VIP'],
            ['number' => '10', 'capacity' => 6, 'location' => 'VIP'],
            ['number' => '11', 'capacity' => 4, 'location' => 'Jardín'],
            ['number' => '12', 'capacity' => 2, 'location' => 'Jardín'],
        ];

        foreach ($tables as $table) {
            Table::updateOrCreate(
                ['number' => $table['number']],
                $table
            );
        }
    }
}