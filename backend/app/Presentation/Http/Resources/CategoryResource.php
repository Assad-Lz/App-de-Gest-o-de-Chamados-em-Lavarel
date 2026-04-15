<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources;

use App\Domain\Category\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource para serialização de Categorias.
 *
 * Transforma a entidade de Domínio em JSON padronizado
 * para as respostas da API. Segue o princípio SRP:
 * responsável apenas pela transformação de dados.
 *
 * @mixin Category
 */
class CategoryResource extends JsonResource
{
    /**
     * Serializa a Categoria para o formato JSON da API.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Category $category */
        $category = $this->resource;

        return [
            'id'         => $category->getId(),
            'name'          => $category->getName(),
            'created_by'    => $category->getCreatedBy(),
            'tickets_count' => $category->getTicketsCount(),
            'created_at'    => $category->getCreatedAt()?->format('Y-m-d\TH:i:s\Z'),
        ];
    }
}
