<?php

/*
 * -------------------------------------------------------
 * Database :: Migration – Tabela categories
 * -------------------------------------------------------
 * Cria a tabela de categorias no banco de dados PostgreSQL.
 * Segue os campos definidos na especificação do teste técnico.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para criação da tabela `categories`.
 *
 * Campos obrigatórios pela spec:
 *   - id, name, created_at, created_by
 */
return new class extends Migration
{
    /**
     * Executa a migration – cria a tabela categories.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            // Chave primária auto-incrementada
            $table->id();

            // Nome da categoria – único para evitar duplicatas
            $table->string('name', 255)->unique();

            // Identificador do criador (IP ou nome do usuário)
            $table->string('created_by', 255)->default('sistema');

            // Apenas created_at (sem updated_at conforme spec)
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverte a migration – remove a tabela categories.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
