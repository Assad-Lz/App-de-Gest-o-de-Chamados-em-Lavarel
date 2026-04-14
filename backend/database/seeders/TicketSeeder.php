<?php

/*
 * -------------------------------------------------------
 * Database :: Seeder – Chamados (Tickets)
 * -------------------------------------------------------
 * Popula a tabela tickets com dados de demonstração.
 * Depende do CategorySeeder (deve ser executado após).
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder de Tickets – cria chamados de demonstração.
 */
class TicketSeeder extends Seeder
{
    /**
     * Insere chamados de demonstração com diferentes status e categorias.
     */
    public function run(): void
    {
        // Busca os IDs das categorias criadas pelo CategorySeeder
        $categorias = DB::table('categories')->pluck('id', 'name');

        $tickets = [
            [
                'title'       => 'Servidor de produção fora do ar',
                'description' => 'O servidor principal de produção apresentou falha e está indisponível desde as 14h. Clientes não conseguem acessar o sistema.',
                'status'      => 'aberto',
                'category_id' => $categorias['Infraestrutura'] ?? 1,
                'created_by'  => '192.168.1.10',
            ],
            [
                'title'       => 'Bug na geração de relatórios PDF',
                'description' => 'O módulo de relatórios está gerando PDFs corrompidos para arquivos acima de 50MB. Erro identificado no handler de exportação.',
                'status'      => 'em_progresso',
                'category_id' => $categorias['Desenvolvimento'] ?? 3,
                'created_by'  => '192.168.1.15',
            ],
            [
                'title'       => 'Solicitação de novo colaborador',
                'description' => 'Novo colaborador iniciando na próxima segunda-feira. Solicito criação de conta de acesso e configuração de e-mail corporativo.',
                'status'      => 'resolvido',
                'category_id' => $categorias['Recursos Humanos'] ?? 5,
                'created_by'  => '192.168.1.20',
            ],
            [
                'title'       => 'Certificado SSL expirando em 7 dias',
                'description' => 'O certificado SSL do domínio principal expira em 7 dias. Necessário renovação urgente para evitar interrupção de serviço.',
                'status'      => 'aberto',
                'category_id' => $categorias['Segurança da Informação'] ?? 6,
                'created_by'  => '192.168.1.25',
            ],
            [
                'title'       => 'Erro de importação de nota fiscal',
                'description' => 'O sistema rejeita a importação de notas fiscais do fornecedor X. O XML está dentro do padrão, porém o sistema retorna erro 422.',
                'status'      => 'em_progresso',
                'category_id' => $categorias['Financeiro'] ?? 4,
                'created_by'  => '192.168.1.30',
            ],
            [
                'title'       => 'Computador do departamento comercial não liga',
                'description' => 'O computador da estação 12 do departamento comercial não liga desde hoje cedo. A fonte pode estar com defeito.',
                'status'      => 'aberto',
                'category_id' => $categorias['Suporte Técnico'] ?? 1,
                'created_by'  => '192.168.1.35',
            ],
        ];

        foreach ($tickets as $ticket) {
            DB::table('tickets')->insert([
                ...$ticket,
                'created_at' => now()->subHours(rand(1, 72)),
            ]);
        }

        $this->command->info('✅ Tickets de demonstração criados com sucesso!');
    }
}
