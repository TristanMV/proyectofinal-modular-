<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): array
    {
        // Catálogos iniciales
        $cat1 = \App\Models\Category::create(['name' => 'Sensores']);
        $cat2 = \App\Models\Category::create(['name' => 'Módulos GPS']);

        // Crear usuario de prueba
        $user = \App\Models\User::factory()->create([
            'name' => 'Tristan Munoz',
            'email' => 'tristan@example.com',
        ]);

        // Crear la tienda piloto vinculada a tu usuario
        $store = \App\Models\Store::create([
            'user_id' => $user->id,
            'name' => 'GeoStore Guadalajara Centro',
            'description' => 'Matriz de desarrollo modular',
            'latitud' => '20.6563',
            'longitud' => '-103.3245'
        ]);

        // Crear productos usando la fábrica y vincularlos a la categoría usando el pivote
        \App\Models\Product::factory(8)->create([
            'store_id' => $store->id
        ])->each(function ($product) use ($cat1) {
            $product->categories()->attach($cat1->id, ['assigned_by' => 'HerdSeeder']);
        });

        return [];
    }
}