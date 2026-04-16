<?php

/*
 * -------------------------------------------------------
 * Bootstrap :: Aplicação Laravel
 * -------------------------------------------------------
 * Ponto de entrada da configuração do Laravel 11.
 * Registra providers, middlewares e bindings globais.
 */

use App\Infrastructure\Providers\RepositoryServiceProvider;
use App\Presentation\Http\Middleware\BackendObfuscationMiddleware;
use App\Presentation\Http\Middleware\HoneypotMiddleware;
use App\Presentation\Http\Middleware\HoneypotTrapMiddleware;
use App\Presentation\Http\Middleware\SecurityHeadersMiddleware;
use App\Presentation\Http\Middleware\XssProtectionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // Registra as rotas da API e web
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ────────────────────────────────────────────────
        // Middlewares globais da API (aplicados em TODAS as requisições)
        // ────────────────────────────────────────────────
        $middleware->api(prepend: [
            BackendObfuscationMiddleware::class, // Ocultar backend de scanners
            SecurityHeadersMiddleware::class,   // Cabeçalhos HTTP seguros (Helmet)
            XssProtectionMiddleware::class,     // Sanitização contra XSS
        ]);

        // ────────────────────────────────────────────────
        // Aliases para uso nas rotas
        // ────────────────────────────────────────────────
        $middleware->alias([
            'security.headers' => SecurityHeadersMiddleware::class,
            'xss.protection'   => XssProtectionMiddleware::class,
            'honeypot'         => HoneypotMiddleware::class,
            'honeypot.trap'    => HoneypotTrapMiddleware::class,
            'obfuscation'      => BackendObfuscationMiddleware::class,
        ]);

    })
    ->withProviders([
        // Registra o provider que faz o bind dos repositórios
        RepositoryServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {

        // Renderiza exceções como JSON para requisições de API
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'code'    => $e->getCode() ?: 500,
                ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
            }
        });

    })
    ->create();
