<?php

/*
 * -------------------------------------------------------
 * Infrastructure :: Repositório Eloquent – Ticket
 * -------------------------------------------------------
 * Implementação concreta da interface TicketRepositoryInterface
 * usando Eloquent ORM com suporte a filtros dinâmicos.
 */

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketRepositoryInterface;
use App\Infrastructure\Models\TicketModel;

/**
 * Implementação do repositório de Tickets usando Eloquent ORM.
 */
class EloquentTicketRepository implements TicketRepositoryInterface
{
    /**
     * Retorna todos os tickets com suporte a filtros dinâmicos.
     *
     * @param  array  $filtros  ['status' => '...', 'category_id' => ...]
     * @return Ticket[]
     */
    public function findAll(array $filtros = []): array
    {
        $query = TicketModel::query()
            ->with('category') // Eager loading para evitar N+1
            ->orderBy('created_at', 'desc');

        // Aplica filtro de status se fornecido
        if (!empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }

        // Aplica filtro de categoria se fornecido
        if (!empty($filtros['category_id'])) {
            $query->where('category_id', (int) $filtros['category_id']);
        }

        return $query->get()
            ->map(fn(TicketModel $model) => $this->toDomain($model))
            ->toArray();
    }

    /**
     * Busca um ticket pelo ID.
     *
     * @param  int  $id
     * @return Ticket|null
     */
    public function findById(int $id): ?Ticket
    {
        $model = TicketModel::find($id);

        return $model ? $this->toDomain($model) : null;
    }

    /**
     * Persiste um novo ticket.
     *
     * @param  Ticket  $ticket
     * @return Ticket Ticket com ID atribuído pelo banco
     */
    public function save(Ticket $ticket): Ticket
    {
        $model = TicketModel::create([
            'title'       => $ticket->getTitle(),
            'description' => $ticket->getDescription(),
            'status'      => $ticket->getStatus(),
            'category_id' => $ticket->getCategoryId(),
            'created_by'  => $ticket->getCreatedBy(),
        ]);

        return $this->toDomain($model);
    }

    /**
     * Atualiza um ticket existente.
     *
     * @param  Ticket  $ticket
     * @return Ticket
     */
    public function update(Ticket $ticket): Ticket
    {
        $model = TicketModel::findOrFail($ticket->getId());

        $model->update([
            'title'       => $ticket->getTitle(),
            'description' => $ticket->getDescription(),
            'status'      => $ticket->getStatus(),
            'category_id' => $ticket->getCategoryId(),
        ]);

        return $this->toDomain($model->fresh());
    }

    /**
     * Remove um ticket pelo ID.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return (bool) TicketModel::destroy($id);
    }

    /**
     * Converte o Model Eloquent para a Entidade de Domínio.
     *
     * @param  TicketModel  $model
     * @return Ticket
     */
    private function toDomain(TicketModel $model): Ticket
    {
        return new Ticket(
            id: $model->id,
            title: $model->title,
            description: $model->description,
            status: $model->status,
            categoryId: $model->category_id,
            createdBy: $model->created_by,
            createdAt: $model->created_at
                ? \DateTimeImmutable::createFromMutable($model->created_at->toDateTime())
                : null,
        );
    }
}
