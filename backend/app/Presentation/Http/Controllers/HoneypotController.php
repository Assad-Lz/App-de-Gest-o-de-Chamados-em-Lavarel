<?php

/*
 * -------------------------------------------------------
 * Presentation :: Controller – Honeypot (Armadilha)
 * -------------------------------------------------------
 * Controller para as rotas-armadilha que simulam endpoints
 * populares de ataque. Retorna respostas convincentes
 * para não alertar o atacante de que foi detectado.
 */

declare(strict_types=1);

namespace App\Presentation\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller Honeypot – responde às rotas-armadilha de forma convincente.
 *
 * O atacante não sabe que está em uma armadilha, o que permite
 * coletar mais informações sobre o padrão de ataque.
 */
class HoneypotController
{
    /**
     * Responde ao acesso de uma rota armadilha.
     *
     * O middleware HoneypotTrapMiddleware já registrou o acesso.
     * Este método apenas retorna uma resposta "convincente" de erro.
     *
     * @return JsonResponse Resposta falsa que imita um sistema real
     */
    public function trap(): JsonResponse
    {
        // Retorna um erro genérico para NÃO revelar que é uma armadilha
        // Um 401 simula que o sistema existe mas requer autenticação
        return response()->json([
            'error'   => 'Unauthorized',
            'message' => 'Autenticação necessária para acessar este recurso.',
            'code'    => 401,
        ], Response::HTTP_UNAUTHORIZED);
    }
}
