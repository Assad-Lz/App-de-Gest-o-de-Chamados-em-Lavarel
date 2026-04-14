<?php

/*
 * -------------------------------------------------------
 * Infrastructure :: Model Eloquent – Ticket
 * -------------------------------------------------------
 * Model do Eloquent ORM que mapeia a tabela `tickets`
 * no banco de dados PostgreSQL (Supabase).
 */

declare(strict_types=1);

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Eloquent para a entidade Ticket (Chamado).
 *
 * @property int         $id
 * @property string      $title
 * @property string      $description
 * @property string      $status
 * @property int         $category_id
 * @property string      $created_by
 * @property \Carbon\Carbon $created_at
 */
class TicketModel extends Model
{
    use HasFactory;

    /**
     * Nome da tabela no banco de dados.
     */
    protected $table = 'tickets';

    /**
     * Desabilita o campo updated_at pois a spec não o requer.
     */
    public const UPDATED_AT = null;

    /**
     * Campos permitidos para preenchimento em massa.
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'category_id',
        'created_by',
    ];

    /**
     * Conversão automática de tipos.
     */
    protected $casts = [
        'created_at'  => 'datetime',
        'category_id' => 'integer',
    ];

    /**
     * Relacionamento: Um ticket pertence a uma categoria (N:1).
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryModel::class, 'category_id');
    }
}
