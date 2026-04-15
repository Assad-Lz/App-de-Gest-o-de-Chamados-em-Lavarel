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
            ->with(['category', 'comments'])
            ->orderBy('created_at', 'desc');

        if (!empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }

        if (!empty($filtros['category_id'])) {
            $query->where('category_id', (int) $filtros['category_id']);
        }

        if (!empty($filtros['search'])) {
            $search = $filtros['search'];
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'ilike', "%{$search}%")
                  ->orWhere('title', 'ilike', "%{$search}%")
                  ->orWhere('user_name', 'ilike', "%{$search}%")
                  ->orWhere('user_email', 'ilike', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        return $query->get()
            ->map(fn(TicketModel $model) => $this->toDomain($model))
            ->toArray();
    }

    public function findById(int $id): ?Ticket
    {
        $model = TicketModel::with(['category', 'comments'])->find($id);

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
            'user_name'    => $ticket->getUserName(),
            'user_email'   => $ticket->getUserEmail(),
            'department'   => $ticket->getDepartment(),
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

    public function deleteMultiple(array $ids): int
    {
        return TicketModel::destroy($ids);
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
            userName: $model->user_name,
            userEmail: $model->user_email,
            department: $model->department,
            ticketNumber: $model->ticket_number,
            createdAt: $model->created_at
                ? \DateTimeImmutable::createFromMutable($model->created_at->toDateTime())
                : null,
            updatedAt: $model->updated_at
                ? \DateTimeImmutable::createFromMutable($model->updated_at->toDateTime())
                : null,
            comments: $model->relationLoaded('comments') ? $model->comments->map(function ($c) {
                return [
                    'id' => $c->id,
                    'comment' => $c->comment,
                    'author_name' => $c->author_name,
                    'author_role' => $c->author_role,
                    'created_at' => $c->created_at->toIso8601String(),
                ];
            })->toArray() : [],
        );
    }
}
