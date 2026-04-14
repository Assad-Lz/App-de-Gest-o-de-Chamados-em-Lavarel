<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources;

use App\Domain\Ticket\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource para serialização de Tickets (Chamados).
 *
 * Transforma a entidade de Domínio em JSON padronizado
 * para as respostas da API.
 *
 * @mixin Ticket
 */
class TicketResource extends JsonResource
{
    /**
     * Serializa o Ticket para o formato JSON da API.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Ticket $ticket */
        $ticket = $this->resource;

        return [
            'id'          => $ticket->getId(),
            'title'       => $ticket->getTitle(),
            'description' => $ticket->getDescription(),
            'status'      => $ticket->getStatus(),
            'category_id' => $ticket->getCategoryId(),
            'created_by'  => $ticket->getCreatedBy(),
            'created_at'  => $ticket->getCreatedAt()?->format('Y-m-d\TH:i:s\Z'),
        ];
    }
}
