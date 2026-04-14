<?php

/*
 * -------------------------------------------------------
 * Database :: Seeder – Categorias
 * -------------------------------------------------------
 * Popula a tabela categories com dados de demonstração.
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder de Categorias – cria categorias de demonstração.
 */
class CategorySeeder extends Seeder
{
    /**
     * Insere as categorias padrão do sistema.
     */
    public function run(): void
    {
        // Categorias típicas de um sistema de suporte
        $categorias = [
            ['name' => 'Suporte Técnico',     'created_by' => 'sistema'],
            ['name' => 'Infraestrutura',       'created_by' => 'sistema'],
            ['name' => 'Desenvolvimento',      'created_by' => 'sistema'],
            ['name' => 'Financeiro',           'created_by' => 'sistema'],
            ['name' => 'Recursos Humanos',     'created_by' => 'sistema'],
            ['name' => 'Segurança da Informação', 'created_by' => 'sistema'],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categories')->insertOrIgnore([
                ...$categoria,
                'created_at' => now(),
            ]);
        }

        $this->command->info('✅ Categorias criadas com sucesso!');
    }
}
