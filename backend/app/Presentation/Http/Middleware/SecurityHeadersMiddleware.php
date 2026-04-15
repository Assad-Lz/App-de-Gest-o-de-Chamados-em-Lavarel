<?php

/*
 * -------------------------------------------------------
 * Presentation :: Middleware – Cabeçalhos de Segurança (Helmet)
 * -------------------------------------------------------
 * Adiciona cabeçalhos HTTP de segurança em todas as respostas,
 * similar ao pacote Helmet do Node.js, prevenindo ataques comuns:
 *
 *   - XSS: Content-Security-Policy
 *   - Clickjacking: X-Frame-Options
 *   - MIME sniffing: X-Content-Type-Options
 *   - HSTS: Strict-Transport-Security
 *   - Referrer leaking: Referrer-Policy
 *
 * Princípio SRP: Este middleware é responsável APENAS por
 * adicionar cabeçalhos HTTP de segurança.
 */

declare(strict_types=1);

namespace App\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de cabeçalhos de segurança HTTP (equivalente ao Helmet.js).
 *
 * Deve ser aplicado globalmente em todas as respostas da API
 * para garantir uma postura de segurança consistente.
 */
class SecurityHeadersMiddleware
{
    /**
     * Processa a requisição e adiciona cabeçalhos de segurança na resposta.
     *
     * @param  Request  $request  Requisição HTTP recebida
     * @param  Closure  $next     Próximo middleware na cadeia
     * @return Response Resposta com cabeçalhos de segurança adicionados
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Impede que o navegador adivinhe o tipo de conteúdo (MIME sniffing)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Impede que a página seja carregada em iframes (clickjacking)
        $response->headers->set('X-Frame-Options', 'DENY');

        // Força HTTPS por 1 ano com subdomínios (HSTS)
        $response->headers->set(
            'Strict-Transport-Security',
            'max-age=31536000; includeSubDomains'
        );

        // Controla o envio do Referrer em requisições cross-origin
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Política de segurança de conteúdo (Content Security Policy)
        // Permite apenas conteúdo do próprio domínio e fontes explicitamente definidas
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self' *; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' *; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com *; " .
            "font-src 'self' https://fonts.gstatic.com *; " .
            "img-src 'self' data: *; " .
            "connect-src 'self' *;"
        );

        // Controla quais APIs do navegador podem ser usadas
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=()'
        );

        // Desabilita o filtro XSS embutido antigo de browsers (obsoleto, mas defensivo)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Remove o cabeçalho padrão que expõe a tecnologia usada
        $response->headers->remove('X-Powered-By');
        $response->headers->set('X-Powered-By', 'GestChamados/1.0');

        return $response;
    }
}
