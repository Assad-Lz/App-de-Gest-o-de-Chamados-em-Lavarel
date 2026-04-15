<?php

/*
 * -------------------------------------------------------
 * Tests :: Unit – Entidade Ticket
 * -------------------------------------------------------
 * Testes unitários para a entidade de domínio Ticket.
 * Abordagem TDD: os testes descrevem o comportamento
 * esperado das regras de negócio do domínio.
 */

declare(strict_types=1);

namespace Tests\Unit\Ticket;

use App\Domain\Ticket\Ticket;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Testes unitários da entidade Ticket.
 *
 * Valida as regras de negócio puras do domínio Ticket:
 *   - Status padrão ao criar via factory method
 *   - Validação de status inválido
 *   - Ciclo de vida do ticket (alteração de status)
 *   - Validação de título obrigatório
 */
class TicketEntityTest extends TestCase
{
    /**
     * Teste 1: O factory method criar() deve definir o status padrão "aberto".
     * Esta é uma regra de negócio crítica definida na especificação.
     *
     * @test
     */
    public function deve_criar_ticket_com_status_aberto_por_padrao(): void
    {
        // Act – usa o factory method do domínio
        $ticket = Ticket::criar(
            title: 'Servidor fora do ar',
            description: 'O servidor principal está inacessível.',
            categoryId: 1,
            createdBy: '127.0.0.1',
            userName: 'Zacky',
            userEmail: 'zacky@cellar.com',
            department: 'TI',
        );

        // Assert – status inicial DEVE ser "aberto"
        $this->assertSame(Ticket::STATUS_ABERTO, $ticket->getStatus());
        $this->assertNull($ticket->getId()); // Ainda sem ID (não persistido)
    }

    /**
     * Teste 2: Deve rejeitar status inválido ao criar ticket diretamente.
     *
     * @test
     */
    public function deve_rejeitar_status_invalido(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Status "invalido" inválido');

        // Act – tenta criar com status não permitido
        new Ticket(
            id: null,
            title: 'Chamado teste',
            description: 'Descrição do chamado.',
            status: 'invalido',   // Status inválido
            categoryId: 1,
            createdBy: 'sistema',
        );
    }

    /**
     * Teste 3: Deve alterar o status do ticket com sucesso.
     * Valida o ciclo de vida: aberto → em_progresso → resolvido.
     *
     * @test
     */
    public function deve_alterar_status_do_ticket_com_sucesso(): void
    {
        // Arrange – cria ticket com status inicial "aberto"
        $ticket = Ticket::criar(
            title: 'Bug no sistema',
            description: 'Descrição do bug.',
            categoryId: 2,
            createdBy: 'sistema',
        );

        $this->assertFalse($ticket->estaResolvido());

        // Act – avança o status para "em_progresso"
        $ticket->alterarStatus(Ticket::STATUS_EM_PROGRESSO);
        $this->assertSame(Ticket::STATUS_EM_PROGRESSO, $ticket->getStatus());

        // Act – resolve o chamado
        $ticket->alterarStatus(Ticket::STATUS_RESOLVIDO);

        // Assert – ticket agora está resolvido
        $this->assertTrue($ticket->estaResolvido());
        $this->assertSame(Ticket::STATUS_RESOLVIDO, $ticket->getStatus());
    }

    /**
     * Teste 4: Deve rejeitar título vazio na criação do ticket.
     *
     * @test
     */
    public function deve_rejeitar_titulo_vazio(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O título do chamado não pode ser vazio.');

        // Act – tenta criar ticket sem título
        Ticket::criar(
            title: '',      // Título inválido
            description: 'Descrição válida.',
            categoryId: 1,
            createdBy: 'sistema',
        );
    }
}
