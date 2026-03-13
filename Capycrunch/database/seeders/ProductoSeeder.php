<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $productos = [
        ['nombre' => 'Galleta Nutella',    'sabor' => 'nutella',     'tipo' => 'unitaria', 'precio' => 10000],
        ['nombre' => 'Galleta Red Velvet', 'sabor' => 'red_velvet',  'tipo' => 'unitaria', 'precio' => 10000],
        ['nombre' => 'Galleta Leche Klim', 'sabor' => 'leche_klim',  'tipo' => 'unitaria', 'precio' => 10000],
        ['nombre' => 'Galleta Pie Limón',  'sabor' => 'pie_limon',   'tipo' => 'unitaria', 'precio' => 10000],
        ['nombre' => 'Galleta Nucita',     'sabor' => 'nucita',      'tipo' => 'unitaria', 'precio' => 10000],
        ['nombre' => 'Bowl de Galletas',   'sabor' => 'mixto',       'tipo' => 'bowl',     'precio' => 60000],
    ];

    foreach ($productos as $p) {
        \App\Models\Producto::create($p);
    }
}
}
