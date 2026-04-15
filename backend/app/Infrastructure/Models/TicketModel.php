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
use Illuminate\Support\Str;

/**
 * Model Eloquent para a entidade Ticket (Chamado).
 *
 * @property int         $id
 * @property string      $ticket_number
 * @property string      $title
 * @property string      $description
 * @property string      $status
 * @property int         $category_id
 * @property string      $created_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class TicketModel extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    /**
     * Mantém o updated_at padrão do Eloquent (agora habilitado).
     */
    public const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'status',
        'category_id',
        'created_by',
    ];

    protected $casts = [
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'category_id' => 'integer',
    ];

    /**
     * Auto-gera ticket_number ao criar um novo ticket.
     */
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->ticket_number)) {
                $year = now()->format('Y');
                // Gera um número sequencial único baseado em timestamp + random
                $seq  = str_pad((string)(now()->getTimestampMs() % 1000000), 6, '0', STR_PAD_LEFT);
                $model->ticket_number = "TK-{$year}-{$seq}";
            }
        });
    }

    /**
     * Relacionamento: Um ticket pertence a uma categoria (N:1).
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryModel::class, 'category_id');
    }
}
