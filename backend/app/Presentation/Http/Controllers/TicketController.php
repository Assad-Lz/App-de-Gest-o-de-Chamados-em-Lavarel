<?php

/*
 * -------------------------------------------------------
 * Presentation :: Controller – Tickets (Chamados)
 * -------------------------------------------------------
 * Controller responsável pelos endpoints REST de Chamados.
 * Orquestra as requisições HTTP, delegando aos Casos de Uso.
 */

declare(strict_types=1);

namespace App\Presentation\Http\Controllers;

use App\Application\Ticket\DTOs\CreateTicketDTO;
use App\Application\Ticket\DTOs\UpdateTicketDTO;
use App\Application\Ticket\UseCases\CreateTicketUseCase;
use App\Application\Ticket\UseCases\DeleteTicketUseCase;
use App\Application\Ticket\UseCases\ListTicketsUseCase;
use App\Application\Ticket\UseCases\UpdateTicketUseCase;
use App\Domain\Ticket\TicketRepositoryInterface;
use App\Presentation\Http\Requests\StoreTicketRequest;
use App\Presentation\Http\Requests\UpdateTicketRequest;
use App\Presentation\Http\Resources\TicketResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller de Chamados (Tickets) – Gestão de Chamados.
 */
class TicketController
{
    public function __construct(
        private readonly ListTicketsUseCase $listUseCase,
        private readonly CreateTicketUseCase $createUseCase,
        private readonly UpdateTicketUseCase $updateUseCase,
        private readonly DeleteTicketUseCase $deleteUseCase,
        private readonly TicketRepositoryInterface $ticketRepository,
    ) {}

    /**
     * GET /api/tickets
     * Retorna chamados com filtros opcionais por status e categoria.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filtros = array_filter([
            'status'      => $request->query('status'),
            'category_id' => $request->query('category_id'),
        ]);

        $tickets = $this->listUseCase->execute($filtros);

        return TicketResource::collection($tickets);
    }

    /**
     * GET /api/tickets/{id}
     * Retorna um chamado específico pelo ID.
     */
    public function show(int $id): JsonResponse
    {
        $ticket = $this->ticketRepository->findById($id);

        if ($ticket === null) {
            return response()->json(
                ['message' => "Chamado com ID {$id} não encontrado."],
                Response::HTTP_NOT_FOUND
            );
        }

        return (new TicketResource($ticket))->response();
    }

    /**
     * POST /api/tickets
     * Cria um novo chamado com status padrão "aberto".
     */
    public function store(StoreTicketRequest $request): JsonResponse
    {
        try {
            $dto = new CreateTicketDTO(
                title: $request->validated('title'),
                description: $request->validated('description'),
                categoryId: (int) $request->validated('category_id'),
                createdBy: $request->validated('created_by') ?? $request->ip() ?? 'sistema',
            );

            $ticket = $this->createUseCase->execute($dto);

            return (new TicketResource($ticket))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);

        } catch (RuntimeException $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    /**
     * PUT /api/tickets/{id}
     * Atualiza um chamado existente.
     */
    public function update(UpdateTicketRequest $request, int $id): JsonResponse
    {
        try {
            $dto = new UpdateTicketDTO(
                title: $request->validated('title'),
                description: $request->validated('description'),
                status: $request->validated('status'),
                categoryId: (int) $request->validated('category_id'),
            );

            $ticket = $this->updateUseCase->execute($id, $dto);

            return (new TicketResource($ticket))->response();

        } catch (RuntimeException $e) {
            $statusCode = str_contains($e->getMessage(), 'não encontrado')
                ? Response::HTTP_NOT_FOUND
                : Response::HTTP_UNPROCESSABLE_ENTITY;

            return response()->json(
                ['message' => $e->getMessage()],
                $statusCode
            );
        }
    }

    /**
     * DELETE /api/tickets/{id}
     * Remove um chamado pelo ID.
     * APENAS para usuários com papel TI/Admin (verificado no frontend).
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->deleteUseCase->execute($id);

            return response()->json(
                ['message' => 'Chamado removido com sucesso.'],
                Response::HTTP_OK
            );

        } catch (RuntimeException $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
