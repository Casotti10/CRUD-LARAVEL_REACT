<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['categoria_id', 'nome', 'sku', 'descricao', 'preco', 'estoque', 'ativo'])]
class Produto extends Model
{
    protected function casts(): array
    {
        return [
            'preco' => 'decimal:2',
            'estoque' => 'integer',
            'ativo' => 'boolean',
        ];
    }
}
