<?php

/*
 * -------------------------------------------------------
 * Infrastructure :: Service Provider – Repositórios
 * -------------------------------------------------------
 * Registra os bindings entre as interfaces de repositório
 * e suas implementações concretas no container IoC do Laravel.
 *
 * Este é o ponto central onde o princípio DIP (Dependency
 * Inversion Principle) do SOLID é materializado:
 * as camadas internas dependem de abstrações (interfaces),
 * e aqui definimos QUAIS implementações usar.
 */

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Domain\Category\CategoryRepositoryInterface;
use App\Domain\Ticket\TicketRepositoryInterface;
use App\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Repositories\EloquentTicketRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Provider de repositórios – registra as implementações concretas.
 *
 * Para trocar de banco de dados (ex: Supabase → MySQL), basta
 * criar novas implementações e trocar os bindings aqui,
 * sem alterar nenhuma camada de Domínio ou Aplicação.
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Registra os bindings de interface → implementação no container IoC.
     *
     * O Laravel usará essas implementações sempre que alguém
     * solicitar os tipos das interfaces via type-hint.
     */
    public function register(): void
    {
        // Bind da interface de Categorias para a implementação Eloquent
        $this->app->bind(
            CategoryRepositoryInterface::class,
            EloquentCategoryRepository::class
        );

        // Bind da interface de Tickets para a implementação Eloquent
        $this->app->bind(
            TicketRepositoryInterface::class,
            EloquentTicketRepository::class
        );
    }

    /**
     * Executa após o registro dos providers.
     * Pode ser usado para publicar configurações ou assets.
     */
    public function boot(): void
    {
        // Nenhuma inicialização necessária neste provider
    }
}
