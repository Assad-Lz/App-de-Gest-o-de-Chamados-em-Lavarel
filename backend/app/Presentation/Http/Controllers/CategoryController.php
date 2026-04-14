<?php

/*
 * -------------------------------------------------------
 * Presentation :: Controller – Categories
 * -------------------------------------------------------
 * Controller responsável pelos endpoints REST de Categorias.
 *
 * Segue os princípios SOLID:
 *   - SRP: Apenas orquestra as requisições HTTP, delegando
 *          lógica de negócio aos Casos de Uso
 *   - DIP: Depende das interfaces dos Casos de Uso, não implementações
 *
 * Clean Architecture: O Controller pertence à camada de Apresentação
 * e NÃO conhece detalhes do banco de dados ou lógica de domínio.
 */

declare(strict_types=1);

namespace App\Presentation\Http\Controllers;

use App\Application\Category\DTOs\CreateCategoryDTO;
use App\Application\Category\DTOs\UpdateCategoryDTO;
use App\Application\Category\UseCases\CreateCategoryUseCase;
use App\Application\Category\UseCases\DeleteCategoryUseCase;
use App\Application\Category\UseCases\ListCategoriesUseCase;
use App\Application\Category\UseCases\UpdateCategoryUseCase;
use App\Presentation\Http\Requests\StoreCategoryRequest;
use App\Presentation\Http\Requests\UpdateCategoryRequest;
use App\Presentation\Http\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller de Categorias – Gestão de Chamados.
 *
 * Todos os métodos seguem o padrão REST e retornam respostas
 * padronizadas via API Resources para consistência.
 */
class CategoryController
{
    /**
     * Injeta os Casos de Uso necessários via construtor (DIP do SOLID).
     *
     * @param  ListCategoriesUseCase   $listUseCase    Caso de uso de listagem
     * @param  CreateCategoryUseCase   $createUseCase  Caso de uso de criação
     * @param  UpdateCategoryUseCase   $updateUseCase  Caso de uso de atualização
     * @param  DeleteCategoryUseCase   $deleteUseCase  Caso de uso de remoção
     */
    public function __construct(
        private readonly ListCategoriesUseCase $listUseCase,
        private readonly CreateCategoryUseCase $createUseCase,
        private readonly UpdateCategoryUseCase $updateUseCase,
        private readonly DeleteCategoryUseCase $deleteUseCase,
    ) {}

    /**
     * GET /api/categories
     * Retorna todas as categorias cadastradas.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = $this->listUseCase->execute();

        return CategoryResource::collection($categories);
    }

    /**
     * POST /api/categories
     * Cria uma nova categoria com os dados validados.
     *
     * @param  StoreCategoryRequest  $request  Requisição validada via Form Request
     * @return JsonResponse Categoria criada com status 201
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $dto = new CreateCategoryDTO(
            name: $request->validated('name'),
            createdBy: $request->ip() ?? 'sistema', // Identifica o criador pelo IP
        );

        $category = $this->createUseCase->execute($dto);

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * PUT /api/categories/{id}
     * Atualiza o nome de uma categoria existente.
     *
     * @param  UpdateCategoryRequest  $request  Requisição validada
     * @param  int                    $id       ID da categoria
     * @return JsonResponse Categoria atualizada ou erro 404
     */
    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        try {
            $dto = new UpdateCategoryDTO(
                name: $request->validated('name'),
            );

            $category = $this->updateUseCase->execute($id, $dto);

            return (new CategoryResource($category))->response();

        } catch (RuntimeException $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * DELETE /api/categories/{id}
     * Remove uma categoria. Falha se houver chamados vinculados.
     *
     * @param  int  $id  ID da categoria a ser removida
     * @return JsonResponse Confirmação ou erro de negócio
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->deleteUseCase->execute($id);

            return response()->json(
                ['message' => 'Categoria removida com sucesso.'],
                Response::HTTP_OK
            );

        } catch (RuntimeException $e) {
            // 422 = Entidade não processável (regra de negócio violada)
            $statusCode = str_contains($e->getMessage(), 'não encontrada')
                ? Response::HTTP_NOT_FOUND
                : Response::HTTP_UNPROCESSABLE_ENTITY;

            return response()->json(
                ['message' => $e->getMessage()],
                $statusCode
            );
        }
    }
}
