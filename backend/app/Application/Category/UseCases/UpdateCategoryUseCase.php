<?php

/*
 * -------------------------------------------------------
 * Application :: Caso de Uso – Atualizar Categoria
 * -------------------------------------------------------
 */

declare(strict_types=1);

namespace App\Application\Category\UseCases;

use App\Application\Category\DTOs\UpdateCategoryDTO;
use App\Domain\Category\Category;
use App\Domain\Category\CategoryRepositoryInterface;
use RuntimeException;

/**
 * Caso de uso: Atualizar uma Categoria existente.
 */
final class UpdateCategoryUseCase
{
    public function __construct(
        private readonly CategoryRepositoryInterface $repository
    ) {}

    /**
     * Executa a atualização da categoria.
     *
     * @param  int                $id   ID da categoria a ser atualizada
     * @param  UpdateCategoryDTO  $dto  Dados novos validados
     * @return Category Categoria atualizada
     * @throws RuntimeException se a categoria não for encontrada
     */
    public function execute(int $id, UpdateCategoryDTO $dto): Category
    {
        // Verifica se a categoria existe antes de atualizar
        $category = $this->repository->findById($id);

        if ($category === null) {
            throw new RuntimeException("Categoria com ID {$id} não encontrada.");
        }

        // Aplica a mudança via método do domínio (encapsula a validação)
        $category->renomear($dto->name);

        return $this->repository->update($category);
    }
}
