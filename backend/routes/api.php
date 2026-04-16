<?php

/*
 * -------------------------------------------------------
 * Rotas da API REST – Gestão de Chamados
 * -------------------------------------------------------
 */

use App\Presentation\Http\Controllers\CategoryController;
use App\Presentation\Http\Controllers\TicketController;
use App\Presentation\Http\Controllers\HoneypotController;
use App\Presentation\Http\Controllers\TicketCommentController;
use Illuminate\Support\Facades\Route;

// -------------------------------------------------------
// Rotas principais da API (v1)
// -------------------------------------------------------
Route::prefix('v1')->middleware(['security.headers', 'xss.protection'])->group(function () {

    // -- Categorias --
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    // -- Chamados (Tickets) --
    Route::prefix('tickets')->group(function () {
        Route::get('/', [TicketController::class, 'index']);           // Listar chamados (com filtros)
        Route::post('/', [TicketController::class, 'store']);          // Criar novo chamado
        Route::get('/{id}', [TicketController::class, 'show']);        // Buscar chamado individual
        Route::put('/{id}', [TicketController::class, 'update']);      // Atualizar chamado
        Route::delete('/{id}', [TicketController::class, 'destroy']);  // Deletar chamado (só TI)
        Route::post('/bulk-delete', [TicketController::class, 'bulkDestroy']); // Excluir múltiplos
        Route::post('/{ticket}/comments', [TicketCommentController::class, 'store']); // Novo Follow Up
    });
});

// -------------------------------------------------------
// Rotas de compatibilidade (sem prefixo v1)
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
        Route::get('/{id}', [TicketController::class, 'show']);
        Route::put('/{id}', [TicketController::class, 'update']);
        Route::delete('/{id}', [TicketController::class, 'destroy']);
        Route::post('/bulk-delete', [TicketController::class, 'bulkDestroy']);
        Route::post('/{ticket}/comments', [TicketCommentController::class, 'store']);
    });
});

// -------------------------------------------------------
// Rotas Honey Pot – Armadilhas para bots e scanners
// -------------------------------------------------------
Route::middleware(['honeypot.trap'])->group(function () {
    Route::any('/admin/login', [HoneypotController::class, 'trap'])->name('honeypot.admin.login');
    Route::any('/wp-admin', [HoneypotController::class, 'trap'])->name('honeypot.wp.admin');
    Route::any('/config/setup', [HoneypotController::class, 'trap'])->name('honeypot.config');
    Route::any('/phpmyadmin', [HoneypotController::class, 'trap'])->name('honeypot.phpmyadmin');
});

// -------------------------------------------------------
// Rota de Escape – Oculta a estrutura do servidor
// -------------------------------------------------------
Route::fallback(fn() => response()->json(['message' => 'Resource not available'], 404));
