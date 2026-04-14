<?php

/*
 * -------------------------------------------------------
 * Application :: Caso de Uso – Listar Categorias
 * -------------------------------------------------------
 * Caso de uso responsável por retornar todas as categorias
 * cadastradas. Segue os princípios SRP e DIP do SOLID.
 */

declare(strict_types=1);

namespace App\Application\Category\UseCases;

use App\Domain\Category\Category;
use App\Domain\Category\CategoryRepositoryInterface;

/**
 * Caso de uso: Listar todas as categorias.
 *
 * Orquestra a consulta ao repositório e retorna a lista
 * de categorias disponíveis no sistema.
 */
final class ListCategoriesUseCase
{
    /**
     * Injeta a dependência via interface (DIP do SOLID).
     *
     * @param  CategoryRepositoryInterface  $repository  Repositório de categorias
     */
    public function __construct(
        private readonly CategoryRepositoryInterface $repository
    ) {}

    /**
     * Executa a listagem de categorias.
     *
     * @return Category[] Lista de todas as categorias
     */
    public function execute(): array
    {
        return $this->repository->findAll();
    }
}
