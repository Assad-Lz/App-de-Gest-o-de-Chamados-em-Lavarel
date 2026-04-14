<?php

/*
 * -------------------------------------------------------
 * Presentation :: Middleware – Proteção contra XSS
 * -------------------------------------------------------
 * Sanitiza todos os dados de entrada das requisições
 * para prevenir ataques de Cross-Site Scripting (XSS).
 *
 * Estratégia de defesa:
 *   1. Remove tags HTML dos campos de entrada (strip_tags)
 *   2. Converte caracteres especiais em entidades HTML seguras
 *   3. Aplica recursivamente em arrays e objetos aninhados
 *
 * Princípios SOLID:
 *   - SRP: Responsável APENAS pela sanitização de inputs
 *   - OCP: Extensível para novos tipos de sanitização
 */

declare(strict_types=1);

namespace App\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de proteção contra Cross-Site Scripting (XSS).
 *
 * Sanitiza recursivamente todos os parâmetros da requisição
 * antes de chegarem aos controllers e camadas internas.
 */
class XssProtectionMiddleware
{
    /**
     * Processa e sanitiza os dados de entrada da requisição.
     *
     * @param  Request  $request  Requisição HTTP com os dados do usuário
     * @param  Closure  $next     Próximo middleware na cadeia
     * @return Response Resposta após processamento seguro
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sanitiza todos os inputs antes de processar a requisição
        $input = $request->all();
        $sanitizado = $this->sanitizarRecursivo($input);
        $request->merge($sanitizado);

        return $next($request);
    }

    /**
     * Sanitiza um valor recursivamente (arrays, strings, etc.).
     *
     * @param  mixed  $valor  Valor a ser sanitizado
     * @return mixed Valor sanitizado
     */
    private function sanitizarRecursivo(mixed $valor): mixed
    {
        if (is_array($valor)) {
            // Aplica sanitização em cada elemento do array
            return array_map(
                fn($item) => $this->sanitizarRecursivo($item),
                $valor
            );
        }

        if (is_string($valor)) {
            return $this->sanitizarString($valor);
        }

        // Tipos não-string (int, bool, null) passam sem modificação
        return $valor;
    }

    /**
     * Sanitiza uma string removendo tags HTML e convertendo caracteres especiais.
     *
     * @param  string  $valor  String a ser sanitizada
     * @return string String segura
     */
    private function sanitizarString(string $valor): string
    {
        // Remove completamente todas as tags HTML e PHP
        $semTags = strip_tags($valor);

        // Converte caracteres especiais em entidades HTML (< > " ' &)
        // Previne a renderização de código malicioso no cliente
        return htmlspecialchars($semTags, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
