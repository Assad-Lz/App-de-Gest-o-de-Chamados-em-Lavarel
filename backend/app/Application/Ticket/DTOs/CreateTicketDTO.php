<?php

declare(strict_types=1);

namespace App\Application\Ticket\DTOs;

/**
 * DTO para criação de um novo Ticket (Chamado).
 *
 * Transporta dados validados entre a camada de Apresentação
 * e o Caso de Uso de criação de chamados.
 */
final readonly class CreateTicketDTO
{
    /**
     * @param  string  $title       Título do chamado
     * @param  string  $description Descrição detalhada
     * @param  int     $categoryId  ID da categoria associada
     * @param  string  $createdBy   Identificador do criador
     */
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly int $categoryId,
        public readonly string $createdBy,
    ) {}
}
