<?php

/*
 * -------------------------------------------------------
 * Database :: Seeder – Dados iniciais do sistema
 * -------------------------------------------------------
 * Popula o banco de dados com dados de demonstração
 * para facilitar o desenvolvimento e apresentação do sistema.
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder principal – executa todos os seeders do sistema.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Executa os seeders em ordem de dependência.
     * Categorias primeiro (tickets dependem delas).
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class, // Deve ser o primeiro (tickets dependem)
            TicketSeeder::class,   // Executado após categories
        ]);
    }
}
