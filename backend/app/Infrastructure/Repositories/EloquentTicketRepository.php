<?php

/*
 * -------------------------------------------------------
 * Infrastructure :: Repositório Eloquent – Ticket
 * -------------------------------------------------------
 */

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketRepositoryInterface;
use App\Infrastructure\Models\TicketModel;

class EloquentTicketRepository implements TicketRepositoryInterface
{
    public function findAll(array $filtros = []): array
    {
        $query = TicketModel::query()
            ->with('category')
            ->orderBy('created_at', 'desc');

        if (!empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }

        if (!empty($filtros['category_id'])) {
            $query->where('category_id', (int) $filtros['category_id']);
        }

        return $query->get()
            ->map(fn(TicketModel $model) => $this->toDomain($model))
            ->toArray();
    }

    public function findById(int $id): ?Ticket
    {
        $model = TicketModel::find($id);

        return $model ? $this->toDomain($model) : null;
    }

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

    public function delete(int $id): bool
    {
        return (bool) TicketModel::destroy($id);
    }

    private function toDomain(TicketModel $model): Ticket
    {
        return new Ticket(
            id: $model->id,
            title: $model->title,
            description: $model->description,
            status: $model->status,
            categoryId: $model->category_id,
            createdBy: $model->created_by,
            ticketNumber: $model->ticket_number,
            createdAt: $model->created_at
                ? \DateTimeImmutable::createFromMutable($model->created_at->toDateTime())
                : null,
            updatedAt: $model->updated_at
                ? \DateTimeImmutable::createFromMutable($model->updated_at->toDateTime())
                : null,
        );
    }
}
