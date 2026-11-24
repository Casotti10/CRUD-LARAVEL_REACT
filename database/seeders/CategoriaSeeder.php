<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    // Ponto de partida da aplicação
    public function run(): void
    {
        $categorias = [
            'Bebidas',
            'Salgadinho',
            'Bolacha',
            'Frios',
            'Doces',
        ];
        foreach ($categorias as $nome) {
            Categoria::firstOrCreate(['nome' => $nome]); // procura pelo nome e só insere se nao existir, nao quebra o unique.
        }
    }
}
