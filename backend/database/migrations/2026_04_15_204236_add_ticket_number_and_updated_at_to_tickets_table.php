<?php

/*
 * -------------------------------------------------------
 * Migration: Adiciona ticket_number e updated_at à tabela tickets
 * -------------------------------------------------------
 * ticket_number: Número de protocolo único e legível (ex: TK-2026-001234)
 * updated_at: Rastreabilidade de edições no chamado
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa a migration.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Número de protocolo único e legível
            $table->string('ticket_number', 20)->nullable()->unique()->after('id');

            // Timestamp de última atualização
            $table->timestamp('updated_at')->nullable()->after('created_at');
        });

        // Preenche os tickets existentes com ticket_number gerado
        $tickets = DB::table('tickets')->orderBy('id')->get(['id', 'created_at']);
        foreach ($tickets as $ticket) {
            $year = date('Y', strtotime($ticket->created_at));
            $number = str_pad($ticket->id, 6, '0', STR_PAD_LEFT);
            DB::table('tickets')->where('id', $ticket->id)->update([
                'ticket_number' => "TK-{$year}-{$number}",
                'updated_at'    => $ticket->created_at,
            ]);
        }

        // Torna o ticket_number obrigatório após o backfill
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('ticket_number', 20)->nullable(false)->change();
        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['ticket_number', 'updated_at']);
        });
    }
};
