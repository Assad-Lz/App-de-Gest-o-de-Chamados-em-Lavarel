<?php

/*
 * -------------------------------------------------------
 * Application :: Caso de Uso – Deletar Categoria
 * -------------------------------------------------------
 * Aplica a regra de negócio: NÃO permite deletar categoria
 * que possua chamados vinculados.
 */

declare(strict_types=1);

namespace App\Application\Category\UseCases;

use App\Domain\Category\CategoryRepositoryInterface;
use RuntimeException;

/**
 * Caso de uso: Remover uma Categoria.
 *
 * Valida a regra de negócio que impede a remoção de categorias
 * que possuam chamados vinculados antes de efetuar a exclusão.
 */
final class DeleteCategoryUseCase
{
    public function __construct(
        private readonly CategoryRepositoryInterface $repository
    ) {}

    /**
     * Executa a remoção de uma categoria.
     *
     * @param  int  $id  Identificador da categoria a ser removida
     * @return bool True se removida com sucesso
     * @throws RuntimeException se a categoria não existir ou possuir chamados
     */
    public function execute(int $id): bool
    {
        // Verifica se a categoria existe
        $category = $this->repository->findById($id);

        if ($category === null) {
            throw new RuntimeException("Categoria com ID {$id} não encontrada.");
        }

        // REGRA DE NEGÓCIO: Não permite deletar categoria com chamados vinculados
        if ($this->repository->hasTickets($id)) {
            throw new RuntimeException(
                'Não é possível deletar a categoria pois existem chamados associados a ela.'
            );
        }

        return $this->repository->delete($id);
    }
}
