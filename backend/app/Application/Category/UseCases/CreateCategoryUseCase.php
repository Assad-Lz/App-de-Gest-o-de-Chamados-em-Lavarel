<?php

/*
 * -------------------------------------------------------
 * Application :: Caso de Uso – Criar Categoria
 * -------------------------------------------------------
 * Recebe o DTO com os dados validados e persiste uma nova
 * categoria através do repositório. Aplica SRP e DIP.
 */

declare(strict_types=1);

namespace App\Application\Category\UseCases;

use App\Application\Category\DTOs\CreateCategoryDTO;
use App\Domain\Category\Category;
use App\Domain\Category\CategoryRepositoryInterface;

/**
 * Caso de uso: Criar uma nova Categoria.
 *
 * Recebe os dados via DTO, constrói a entidade de domínio
 * e delega a persistência ao repositório abstrato.
 */
final class CreateCategoryUseCase
{
    /**
     * Injeta o repositório via interface (DIP).
     *
     * @param  CategoryRepositoryInterface  $repository
     */
    public function __construct(
        private readonly CategoryRepositoryInterface $repository
    ) {}

    /**
     * Executa a criação de uma nova categoria.
     *
     * @param  CreateCategoryDTO  $dto  Dados validados da requisição
     * @return Category Categoria recém-criada com ID atribuído
     */
    public function execute(CreateCategoryDTO $dto): Category
    {
        // Constrói a entidade de domínio (sem ID, será gerado pelo banco)
        $category = new Category(
            id: null,
            name: $dto->name,
            createdBy: $dto->createdBy,
        );

        return $this->repository->save($category);
    }
}
