<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources;

use App\Domain\Ticket\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource para serialização de Tickets (Chamados).
 *
 * @mixin Ticket
 */
class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Ticket $ticket */
        $ticket = $this->resource;

        return [
            'id'            => $ticket->getId(),
            'ticket_number' => $ticket->getTicketNumber(),
            'title'         => $ticket->getTitle(),
            'description'   => $ticket->getDescription(),
            'status'        => $ticket->getStatus(),
            'category_id'   => $ticket->getCategoryId(),
            'created_by'    => $ticket->getCreatedBy(),
            'user_name'     => $ticket->getUserName(),
            'user_email'    => $ticket->getUserEmail(),
            'department'    => $ticket->getDepartment(),
            'created_at'    => $ticket->getCreatedAt()?->format('Y-m-d\TH:i:s\Z'),
            'updated_at'    => $ticket->getUpdatedAt()?->format('Y-m-d\TH:i:s\Z'),
            'comments'      => $ticket->getComments(),
        ];
    }
}
