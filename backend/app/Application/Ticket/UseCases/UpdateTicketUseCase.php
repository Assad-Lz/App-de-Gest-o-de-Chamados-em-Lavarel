<?php

declare(strict_types=1);

namespace App\Application\Ticket\UseCases;

use App\Application\Ticket\DTOs\UpdateTicketDTO;
use App\Domain\Category\CategoryRepositoryInterface;
use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketRepositoryInterface;
use RuntimeException;

/**
 * Caso de uso: Atualizar um Chamado existente.
 */
final class UpdateTicketUseCase
{
    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository,
        private readonly CategoryRepositoryInterface $categoryRepository,
    ) {}

    /**
     * Executa a atualização de um chamado.
     *
     * @param  int              $id   ID do chamado
     * @param  UpdateTicketDTO  $dto  Dados atualizados
     * @return Ticket Chamado atualizado
     * @throws RuntimeException se o ticket ou categoria não existir
     */
    public function execute(int $id, UpdateTicketDTO $dto): Ticket
    {
        // Verifica se o ticket existe
        $ticket = $this->ticketRepository->findById($id);

        if ($ticket === null) {
            throw new RuntimeException("Chamado com ID {$id} não encontrado.");
        }

        // Valida se a nova categoria existe
        $categoria = $this->categoryRepository->findById($dto->categoryId);

        if ($categoria === null) {
            throw new RuntimeException(
                "Categoria com ID {$dto->categoryId} não encontrada."
            );
        }

        // Atualiza os dados via método do domínio (com validação embutida)
        $ticket->atualizar(
            titulo: $dto->title,
            descricao: $dto->description,
            status: $dto->status,
            categoryId: $dto->categoryId,
        );

        return $this->ticketRepository->update($ticket);
    }
}
