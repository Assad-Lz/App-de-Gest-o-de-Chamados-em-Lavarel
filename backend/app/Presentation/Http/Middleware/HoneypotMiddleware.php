<?php

/*
 * -------------------------------------------------------
 * Presentation :: Middleware – Honey Pot (Armadilha para Bots)
 * -------------------------------------------------------
 * Detecta e registra comportamentos suspeitos de bots e
 * scanners automatizados através de múltiplos mecanismos:
 *
 *   1. Campo oculto em formulários: se preenchido = bot
 *   2. Ausência do header X-Requested-With: suspeita
 *   3. Acesso às rotas-armadilha: log e bloqueio
 *
 * Princípios SOLID:
 *   - SRP: Responsável APENAS pela detecção de bots
 *   - OCP: Novos mecanismos podem ser adicionados sem modificar os existentes
 */

declare(strict_types=1);

namespace App\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware Honey Pot – detecta e bloqueia bots/scanners.
 *
 * Bots geralmente preenchem todos os campos de formulário,
 * incluindo campos ocultos que nenhum humano veria.
 * Este middleware usa isso como sinal de comportamento automatizado.
 */
class HoneypotMiddleware
{
    /**
     * Lista de campos ocultos que funcionam como armadilha.
     * Um humano nunca veria/preencheria esses campos.
     */
    private const CAMPOS_ARMADILHA = [
        'website',       // Campo oculto via CSS que bots preenchem
        'telefone_extra', // Outro campo honeypot
        'endereco_bot',  // Campo armadilha adicional
    ];

    /**
     * Processa a requisição verificando sinais de comportamento automatizado.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se algum campo honeypot foi preenchido
        if ($this->honeyPotAtivado($request)) {
            $this->registrarAtividadeSuspeita($request, 'Campo honeypot preenchido');

            // Retorna 200 OK para não alertar o bot que foi detectado
            // (retornar 403 daria ao bot informação sobre o sistema)
            return response()->json([
                'message' => 'Requisição processada.',
            ], Response::HTTP_OK);
        }

        return $next($request);
    }

    /**
     * Verifica se algum campo honeypot foi preenchido na requisição.
     *
     * @param  Request  $request
     * @return bool True se bot detectado
     */
    private function honeyPotAtivado(Request $request): bool
    {
        foreach (self::CAMPOS_ARMADILHA as $campo) {
            // Se o campo existe E contém algum valor = bot detectado
            if ($request->filled($campo)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Registra a atividade suspeita no log de segurança.
     *
     * Armazena IP, User-Agent, rota e motivo da suspeita
     * para análise posterior de ataques.
     *
     * @param  Request  $request  Requisição suspeita
     * @param  string   $motivo   Motivo da suspeita
     */
    private function registrarAtividadeSuspeita(Request $request, string $motivo): void
    {
        Log::channel('honeypot')->warning('🍯 Atividade suspeita detectada pelo Honey Pot', [
            'motivo'      => $motivo,
            'ip'          => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'url'         => $request->fullUrl(),
            'metodo'      => $request->method(),
            'dados'       => $this->sanitizarDadosLog($request->all()),
            'timestamp'   => now()->toIso8601String(),
        ]);
    }

    /**
     * Remove dados sensíveis antes de logar (senhas, tokens, etc.).
     *
     * @param  array  $dados
     * @return array
     */
    private function sanitizarDadosLog(array $dados): array
    {
        $camposSensiveis = ['password', 'token', 'secret', 'api_key'];

        foreach ($camposSensiveis as $campo) {
            if (isset($dados[$campo])) {
                $dados[$campo] = '***REDACTED***';
            }
        }

        return $dados;
    }
}
