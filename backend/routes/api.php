<?php

/*
 * -------------------------------------------------------
 * Rotas da API REST – Gestão de Chamados
 * -------------------------------------------------------
 * Todas as rotas seguem os princípios RESTful e passam
 * pelos middlewares de segurança definidos no Kernel.
 *
 * Middlewares aplicados globalmente via RouteServiceProvider:
 *   - api: throttle:api, SubstituteBindings
 *   - SecurityHeadersMiddleware: cabeçalhos HTTP seguros (Helmet)
 *   - XssProtectionMiddleware: sanitização contra XSS
 *   - HoneypotMiddleware: detecção e bloqueio de bots
 */

use App\Presentation\Http\Controllers\CategoryController;
use App\Presentation\Http\Controllers\TicketController;
use App\Presentation\Http\Controllers\HoneypotController;
use Illuminate\Support\Facades\Route;

// -------------------------------------------------------
// Rotas principais da API (v1)
// -------------------------------------------------------
Route::prefix('v1')->middleware(['security.headers', 'xss.protection'])->group(function () {

    // -- Categorias --
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);        // Listar todas as categorias
        Route::post('/', [CategoryController::class, 'store']);       // Criar nova categoria
        Route::put('/{id}', [CategoryController::class, 'update']);  // Atualizar categoria
        Route::delete('/{id}', [CategoryController::class, 'destroy']); // Deletar categoria
    });

    // -- Chamados (Tickets) --
    Route::prefix('tickets')->group(function () {
        Route::get('/', [TicketController::class, 'index']);        // Listar chamados (com filtros)
        Route::post('/', [TicketController::class, 'store']);       // Criar novo chamado
        Route::put('/{id}', [TicketController::class, 'update']);  // Atualizar chamado
        Route::delete('/{id}', [TicketController::class, 'destroy']); // Deletar chamado
    });
});

// -------------------------------------------------------
// Rotas de compatibilidade (sem prefixo v1) – conforme spec
// -------------------------------------------------------
Route::middleware(['security.headers', 'xss.protection'])->group(function () {

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    Route::prefix('tickets')->group(function () {
        Route::get('/', [TicketController::class, 'index']);
        Route::post('/', [TicketController::class, 'store']);
        Route::put('/{id}', [TicketController::class, 'update']);
        Route::delete('/{id}', [TicketController::class, 'destroy']);
    });
});

// -------------------------------------------------------
// Rotas Honey Pot – Armadilhas para bots e scanners
// -------------------------------------------------------
Route::middleware(['honeypot.trap'])->group(function () {

    // Armadilha 1: Rota falsa de login administrativo
    Route::any('/admin/login', [HoneypotController::class, 'trap'])
        ->name('honeypot.admin.login');

    // Armadilha 2: Rota falsa de painel wp-admin (scanner WordPress)
    Route::any('/wp-admin', [HoneypotController::class, 'trap'])
        ->name('honeypot.wp.admin');

    // Armadilha 3: Rota falsa de configuração
    Route::any('/config/setup', [HoneypotController::class, 'trap'])
        ->name('honeypot.config');

    // Armadilha 4: Rota falsa de phpMyAdmin
    Route::any('/phpmyadmin', [HoneypotController::class, 'trap'])
        ->name('honeypot.phpmyadmin');
});
