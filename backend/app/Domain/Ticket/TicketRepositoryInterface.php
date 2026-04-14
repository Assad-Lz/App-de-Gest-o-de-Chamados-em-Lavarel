<?php

/*
 * -------------------------------------------------------
 * Domain :: Contrato do Repositório de Tickets (Chamados)
 * -------------------------------------------------------
 * Interface que define as operações de persistência para Tickets.
 * Segue o princípio DIP (Dependency Inversion Principle) do SOLID.
 */

declare(strict_types=1);

namespace App\Domain\Ticket;

/**
 * Contrato (interface) para o repositório de Tickets.
 *
 * Define as operações de persistência e consulta necessárias
 * para o domínio de Chamados. Implementações concretas ficam
 * na camada de Infraestrutura.
 */
interface TicketRepositoryInterface
{
    /**
     * Retorna todos os tickets com suporte a filtros.
     *
     * @param  array  $filtros  Array associativo com filtros opcionais:
     *                          - 'status': filtrar por status
     *                          - 'category_id': filtrar por categoria
     * @return Ticket[] Lista de tickets filtrados
     */
    public function findAll(array $filtros = []): array;

    /**
     * Busca um ticket pelo seu ID único.
     *
     * @param  int  $id  Identificador do ticket
     * @return Ticket|null Ticket encontrado ou null
     */
    public function findById(int $id): ?Ticket;

    /**
     * Persiste um novo ticket no banco de dados.
     *
     * @param  Ticket  $ticket  Entidade a ser persistida
     * @return Ticket Ticket persistido com ID gerado
     */
    public function save(Ticket $ticket): Ticket;

    /**
     * Atualiza os dados de um ticket existente.
     *
     * @param  Ticket  $ticket  Entidade com os novos dados
     * @return Ticket Ticket atualizado
     */
    public function update(Ticket $ticket): Ticket;

    /**
     * Remove um ticket pelo ID.
     *
     * @param  int  $id  Identificador do ticket a ser removido
     * @return bool True se removido com sucesso
     */
    public function delete(int $id): bool;
}
