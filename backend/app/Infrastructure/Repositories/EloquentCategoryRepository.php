<?php

/*
 * -------------------------------------------------------
 * Infrastructure :: Repositório Eloquent – Category
 * -------------------------------------------------------
 * Implementação concreta da interface CategoryRepositoryInterface
 * usando o Eloquent ORM do Laravel conectado ao Supabase.
 *
 * Converte entre o Model Eloquent (infraestrutura) e a
 * Entidade de Domínio (Category), mantendo o isolamento entre camadas.
 */

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Category\Category;
use App\Domain\Category\CategoryRepositoryInterface;
use App\Infrastructure\Models\CategoryModel;

/**
 * Implementação do repositório de Categorias usando Eloquent ORM.
 *
 * Faz a ponte entre o banco de dados PostgreSQL (Supabase) e
 * as entidades de domínio, seguindo o padrão Repository Pattern.
 */
class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Retorna todas as categorias ordenadas pelo nome.
     *
     * @return Category[]
     */
    public function findAll(): array
    {
        return CategoryModel::orderBy('name')
            ->get()
            ->map(fn(CategoryModel $model) => $this->toDomain($model))
            ->toArray();
    }

    /**
     * Busca uma categoria pelo ID.
     *
     * @param  int  $id
     * @return Category|null
     */
    public function findById(int $id): ?Category
    {
        $model = CategoryModel::find($id);

        return $model ? $this->toDomain($model) : null;
    }

    /**
     * Persiste uma nova categoria no banco de dados.
     *
     * @param  Category  $category
     * @return Category Categoria com ID atribuído pelo banco
     */
    public function save(Category $category): Category
    {
        $model = CategoryModel::create([
            'name'       => $category->getName(),
            'created_by' => $category->getCreatedBy(),
        ]);

        return $this->toDomain($model);
    }

    /**
     * Atualiza uma categoria existente.
     *
     * @param  Category  $category
     * @return Category
     */
    public function update(Category $category): Category
    {
        $model = CategoryModel::findOrFail($category->getId());
        $model->update(['name' => $category->getName()]);

        return $this->toDomain($model->fresh());
    }

    /**
     * Remove uma categoria pelo ID.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return (bool) CategoryModel::destroy($id);
    }

    /**
     * Verifica se a categoria possui chamados vinculados.
     * Usado para validar a regra de negócio antes de deletar.
     *
     * @param  int  $categoryId
     * @return bool
     */
    public function hasTickets(int $categoryId): bool
    {
        return CategoryModel::where('id', $categoryId)
            ->whereHas('tickets')
            ->exists();
    }

    /**
     * Converte o Model Eloquent para a Entidade de Domínio.
     *
     * Este mapeamento mantém o isolamento entre as camadas:
     * a camada de Domínio nunca conhece o Eloquent Model.
     *
     * @param  CategoryModel  $model
     * @return Category
     */
    private function toDomain(CategoryModel $model): Category
    {
        return new Category(
            id: $model->id,
            name: $model->name,
            createdBy: $model->created_by,
            createdAt: $model->created_at
                ? \DateTimeImmutable::createFromMutable($model->created_at->toDateTime())
                : null,
        );
    }
}
