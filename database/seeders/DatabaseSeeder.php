<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Define a ordem, o ProdutoSeeder vai dps de Categoria Seeder porque o produto depende da categoria
        $this->call([
            CategoriaSeeder::class,
        ]);

    }
}
