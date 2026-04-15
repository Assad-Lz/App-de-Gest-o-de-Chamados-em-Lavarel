<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers;

use App\Infrastructure\Models\TicketCommentModel;
use App\Infrastructure\Models\TicketModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketCommentController
{
    /**
     * POST /api/tickets/{ticket}/comments
     * Adiciona um follow-up (comentário) ao chamado.
     */
    public function store(Request $request, int $ticketId): JsonResponse
    {
        $request->validate([
            'comment'     => 'required|string|max:5000',
            'author_name' => 'required|string|max:255',
            'author_role' => 'required|string|in:analista,cliente',
        ]);

        $ticket = TicketModel::findOrFail($ticketId);

        $comment = TicketCommentModel::create([
            'ticket_id'   => $ticket->id,
            'comment'     => $request->comment,
            'author_name' => $request->author_name,
            'author_role' => $request->author_role,
        ]);

        return response()->json([
            'message' => 'Comentário adicionado com sucesso!',
            'data'    => $comment
        ], Response::HTTP_CREATED);
    }
}
