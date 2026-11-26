<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['categoria_id', 'nome', 'sku', 'descricao', 'preco', 'estoque', 'ativo'])]
class Produto extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'preco' => 'decimal:2',
            'estoque' => 'integer',
            'ativo' => 'boolean',
        ];
    }

    /**
     * Categoria à qual o produto pertence.
     *
     * @return BelongsTo<Categoria, $this>
     */
    public function categoria(): BelongsTo // devolve o construtor de consulta do relacionamento, ainda sem executar
    {
        return $this->belongsTo(Categoria::class);
    }
}
