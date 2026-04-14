<?php

declare(strict_types=1);

namespace App\Application\Ticket\DTOs;

/**
 * DTO para atualização de um Ticket (Chamado) existente.
 */
final readonly class UpdateTicketDTO
{
    /**
     * @param  string  $title       Novo título
     * @param  string  $description Nova descrição
     * @param  string  $status      Novo status (aberto, em_progresso, resolvido)
     * @param  int     $categoryId  Novo ID de categoria
     */
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $status,
        public readonly int $categoryId,
    ) {}
}
