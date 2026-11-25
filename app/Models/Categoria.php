<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nome'])]
class Categoria extends Model
{
    /**
     * Produtos desta categoria.
     *
     * @return HasMany<Produto, $this>
     */
    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class);  // Eloquent deduz a FK pelo nome do model atual
    }
}
