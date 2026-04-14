<?php

declare(strict_types=1);

namespace App\Application\Ticket\UseCases;

use App\Application\Ticket\DTOs\CreateTicketDTO;
use App\Domain\Category\CategoryRepositoryInterface;
use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketRepositoryInterface;
use RuntimeException;

/**
 * Caso de uso: Criar um novo Chamado (Ticket).
 *
 * Valida que a categoria existe antes de criar o chamado,
 * aplicando a regra de negócio de vínculo obrigatório com categoria.
 */
final class CreateTicketUseCase
{
    /**
     * @param  TicketRepositoryInterface    $ticketRepository
     * @param  CategoryRepositoryInterface  $categoryRepository  Validar existência da categoria
     */
    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository,
        private readonly CategoryRepositoryInterface $categoryRepository,
    ) {}

    /**
     * Executa a criação de um novo chamado.
     *
     * @param  CreateTicketDTO  $dto  Dados validados do chamado
     * @return Ticket Chamado criado com ID atribuído
     * @throws RuntimeException se a categoria não existir
     */
    public function execute(CreateTicketDTO $dto): Ticket
    {
        // REGRA DE NEGÓCIO: A categoria deve existir antes de criar o chamado
        $categoria = $this->categoryRepository->findById($dto->categoryId);

        if ($categoria === null) {
            throw new RuntimeException(
                "Categoria com ID {$dto->categoryId} não encontrada. O chamado deve ter uma categoria válida."
            );
        }

        // Factory Method do domínio garante status padrão "aberto"
        $ticket = Ticket::criar(
            title: $dto->title,
            description: $dto->description,
            categoryId: $dto->categoryId,
            createdBy: $dto->createdBy,
        );

        return $this->ticketRepository->save($ticket);
    }
}
