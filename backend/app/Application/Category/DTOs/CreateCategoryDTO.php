<?php

/*
 * -------------------------------------------------------
 * Application :: DTO – Criar Categoria
 * -------------------------------------------------------
 * Data Transfer Object para transferir dados validados
 * entre a camada de Apresentação e a camada de Aplicação.
 *
 * Um DTO é imutável (readonly) e não contém lógica de negócio.
 */

declare(strict_types=1);

namespace App\Application\Category\DTOs;

/**
 * DTO para criação de uma nova Categoria.
 *
 * Transporta os dados já validados pela Form Request
 * da camada de Apresentação até o Caso de Uso.
 */
final readonly class CreateCategoryDTO
{
    /**
     * @param  string  $name       Nome da nova categoria
     * @param  string  $createdBy  Identificador do criador (ex: IP, "sistema")
     */
    public function __construct(
        public readonly string $name,
        public readonly string $createdBy,
    ) {}
}
