<?php

declare(strict_types=1);

namespace App\Application\Category\DTOs;

/**
 * DTO para atualização de uma Categoria existente.
 */
final readonly class UpdateCategoryDTO
{
    /**
     * @param  string  $name  Novo nome para a categoria
     */
    public function __construct(
        public readonly string $name,
    ) {}
}
