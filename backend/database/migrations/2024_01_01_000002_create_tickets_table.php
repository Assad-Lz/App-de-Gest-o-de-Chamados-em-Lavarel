<?php

/*
 * -------------------------------------------------------
 * Database :: Migration – Tabela tickets
 * -------------------------------------------------------
 * Cria a tabela de chamados no banco de dados PostgreSQL.
 * Segue os campos definidos na especificação do teste técnico.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para criação da tabela `tickets`.
 *
 * Campos obrigatórios pela spec:
 *   - id, title, description, status, category_id, created_at, created_by
 *
 * Regras de negócio implementadas na migration:
 *   - category_id: chave estrangeira obrigatória
 *   - status padrão: 'aberto'
 */
return new class extends Migration
{
    /**
     * Executa a migration – cria a tabela tickets.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            // Chave primária auto-incrementada
            $table->id();

            // Título do chamado
            $table->string('title', 255);

            // Descrição detalhada do problema
            $table->text('description');

            // Status com valores permitidos e padrão "aberto"
            // Usando string simples para compatibilidade com Supabase
            $table->string('status', 20)->default('aberto');

            // Chave estrangeira obrigatória para a categoria
            $table->foreignId('category_id')
                ->constrained('categories')
                ->onDelete('restrict'); // Impede deleção de categoria com tickets

            // Identificador do criador
            $table->string('created_by', 255)->default('sistema');

            // Apenas created_at (sem updated_at conforme spec)
            $table->timestamp('created_at')->useCurrent();

            // Índices para performance nas queries de filtro
            $table->index('status');
            $table->index('category_id');
        });
    }

    /**
     * Reverte a migration – remove a tabela tickets.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
