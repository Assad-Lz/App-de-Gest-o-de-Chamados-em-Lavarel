<?php

/*
 * -------------------------------------------------------
 * Presentation :: Middleware – Honeypot Trap (Rotas Armadilha)
 * -------------------------------------------------------
 * Middleware específico para as rotas-armadilha que simulam
 * endpoints populares de ataque (wp-admin, phpmyadmin, etc.).
 *
 * Qualquer acesso a essas rotas é considerado atividade maliciosa
 * e registrado para análise de segurança.
 */

declare(strict_types=1);

namespace App\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para rotas-armadilha (Honey Pot Routes).
 *
 * Registra e responde a acessos suspeitos a rotas
 * que nenhum usuário legítimo acessaria.
 */
class HoneypotTrapMiddleware
{
    /**
     * Processa o acesso à rota armadilha.
     * Registra o acesso e responde com uma página falsa convincente.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Registra o acesso suspeito com todos os dados disponíveis
        Log::channel('honeypot')->critical('🍯 Acesso a rota armadilha detectado!', [
            'ip'           => $request->ip(),
            'user_agent'   => $request->userAgent() ?? 'Desconhecido',
            'rota'         => $request->path(),
            'metodo'       => $request->method(),
            'headers'      => $this->filtrarHeaders($request->headers->all()),
            'body'         => $request->all(),
            'timestamp'    => now()->toIso8601String(),
            'severidade'   => 'CRÍTICA',
        ]);

        // Adiciona pequeno delay para frustrar scanners automatizados
        usleep(500000); // 0.5 segundo de delay

        // Passa para o controller da armadilha que retorna resposta convincente
        return $next($request);
    }

    /**
     * Filtra headers para remover dados desnecessários no log.
     *
     * @param  array  $headers  Headers da requisição
     * @return array Headers filtrados relevantes
     */
    private function filtrarHeaders(array $headers): array
    {
        $headersRelevantes = [
            'user-agent', 'referer', 'origin', 'x-forwarded-for',
            'accept', 'content-type', 'authorization',
        ];

        return array_filter(
            $headers,
            fn($chave) => in_array(strtolower($chave), $headersRelevantes, true),
            ARRAY_FILTER_USE_KEY
        );
    }
}
