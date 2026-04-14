<?php

declare(strict_types=1);

namespace App\Application\Ticket\UseCases;

use App\Domain\Ticket\TicketRepositoryInterface;
use RuntimeException;

/**
 * Caso de uso: Remover um Chamado.
 */
final class DeleteTicketUseCase
{
    public function __construct(
        private readonly TicketRepositoryInterface $repository
    ) {}

    /**
     * Executa a remoção de um chamado.
     *
     * @param  int  $id  ID do chamado a ser removido
     * @return bool True se removido com sucesso
     * @throws RuntimeException se o chamado não existir
     */
    public function execute(int $id): bool
    {
        $ticket = $this->repository->findById($id);

        if ($ticket === null) {
            throw new RuntimeException("Chamado com ID {$id} não encontrado.");
        }

        return $this->repository->delete($id);
    }
}
