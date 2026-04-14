<?php

/*
 * -------------------------------------------------------
 * Infrastructure :: Model Eloquent – Category
 * -------------------------------------------------------
 * Model do Eloquent ORM que mapeia a tabela `categories`
 * no banco de dados PostgreSQL (Supabase).
 *
 * Esta classe pertence à camada de Infraestrutura e não
 * deve ser usada diretamente nas camadas de Domínio ou Aplicação.
 */

declare(strict_types=1);

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Eloquent para a entidade Category (Categoria).
 *
 * @property int         $id
 * @property string      $name
 * @property string      $created_by
 * @property \Carbon\Carbon $created_at
 */
class CategoryModel extends Model
{
    use HasFactory;

    /**
     * Nome da tabela no banco de dados.
     */
    protected $table = 'categories';

    /**
     * Desabilita o campo updated_at pois a spec não o requer.
     */
    public const UPDATED_AT = null;

    /**
     * Campos que podem ser preenchidos em massa (Mass Assignment Protection).
     */
    protected $fillable = [
        'name',
        'created_by',
    ];

    /**
     * Conversão automática de tipos dos campos.
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relacionamento: Uma categoria possui muitos chamados (1:N).
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(TicketModel::class, 'category_id');
    }
}
