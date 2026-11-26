<?php

namespace Database\Factories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Produto>
 */
class ProdutoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => ucfirst(fake()->words(3, true)),
            'sku' => strtoupper(fake()->unique()->bothify('???-####')),
            'descricao' => fake()->optional()->sentence(),
            'preco' => fake()->randomFloat(2, 1, 500),
            'estoque' => fake()->numberBetween(0, 200),
            'ativo' => fake()->boolean(85),
        ];
    }
}
