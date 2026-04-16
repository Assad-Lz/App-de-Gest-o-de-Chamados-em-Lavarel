<?php

declare(strict_types=1);

namespace App\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para ocultar a identidade do backend e restringir acessos.
 */
class BackendObfuscationMiddleware
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificar se o Host é permitido (impede acesso direto por IP se configurado)
        $allowedHosts = [parse_url(config('app.url'), PHP_URL_HOST), 'localhost', '127.0.0.1'];
        if (!in_array($request->getHost(), $allowedHosts) && app()->environment('production')) {
             return response()->json(['error' => 'Not Found'], 404);
        }

        // 2. Rejeitar User-Agents conhecidos de scanners comuns
        $userAgent = $request->header('User-Agent', '');
        $badAgents = ['Nuclei', 'WikiDo', 'Nmap', 'python-requests', 'GuzzleHttp', 'Go-http-client'];
        
        foreach ($badAgents as $agent) {
            if (stripos($userAgent, $agent) !== false) {
                // Simula uma falha de conexão ou 404 para "sumir" do scanner
                return response()->json(['message' => 'Service Unavailable'], 503);
            }
        }

        $response = $next($request);

        // 3. Garantir que headers de servidor não vazem (redundância defensiva)
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('X-Runtime');
        $response->headers->remove('X-Version');

        return $response;
    }
}
