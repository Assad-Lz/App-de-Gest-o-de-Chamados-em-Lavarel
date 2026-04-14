<?php

/*
 * -------------------------------------------------------
 * Domain :: Contrato do Repositório de Categorias
 * -------------------------------------------------------
 * Interface que define o contrato de persistência para
 * Categorias. Segue o padrão Repository Pattern e
 * o princípio DIP (Dependency Inversion Principle) do SOLID:
 * os casos de uso dependem desta abstração, não de
 * implementações concretas (Eloquent, Supabase, etc).
 */

declare(strict_types=1);

namespace App\Domain\Category;

/**
 * Contrato (interface) para o repositório de Categorias.
 *
 * Define as operações de persistência necessárias para
 * o domínio de Categorias. As implementações concretas
 * (EloquentCategoryRepository) ficam na camada de Infraestrutura.
 */
interface CategoryRepositoryInterface
{
    /**
     * Retorna todas as categorias ordenadas por nome.
     *
     * @return Category[] Lista de todas as categorias
     */
    public function findAll(): array;

    /**
     * Busca uma categoria pelo seu ID único.
     *
     * @param  int  $id  Identificador da categoria
     * @return Category|null Categoria encontrada ou null se não existir
     */
    public function findById(int $id): ?Category;

    /**
     * Persiste uma nova categoria no banco de dados.
     *
     * @param  Category  $category  Entidade a ser persistida
     * @return Category Categoria persistida com ID gerado
     */
    public function save(Category $category): Category;

    /**
     * Atualiza os dados de uma categoria existente.
     *
     * @param  Category  $category  Entidade com os novos dados
     * @return Category Categoria atualizada
     */
    public function update(Category $category): Category;

    /**
     * Remove uma categoria pelo ID.
     *
     * @param  int  $id  Identificador da categoria a ser removida
     * @return bool True se removida com sucesso
     */
    public function delete(int $id): bool;

    /**
     * Verifica se uma categoria possui chamados vinculados.
     * Regra de negócio: categoria com chamados NÃO pode ser deletada.
     *
     * @param  int  $categoryId  Identificador da categoria
     * @return bool True se houver chamados associados
     */
    public function hasTickets(int $categoryId): bool;
}
