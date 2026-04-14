<?php

declare(strict_types=1);

namespace App\Application\Ticket\UseCases;

use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketRepositoryInterface;

/**
 * Caso de uso: Listar Chamados com suporte a filtros.
 *
 * Aceita filtros opcionais por status e categoria para
 * retornar apenas os chamados relevantes para o usuário.
 */
final class ListTicketsUseCase
{
    public function __construct(
        private readonly TicketRepositoryInterface $repository
    ) {}

    /**
     * Executa a listagem de tickets com filtros opcionais.
     *
     * @param  array  $filtros  Filtros: ['status' => '...', 'category_id' => ...]
     * @return Ticket[] Lista de tickets filtrados
     */
    public function execute(array $filtros = []): array
    {
        return $this->repository->findAll($filtros);
    }
}
