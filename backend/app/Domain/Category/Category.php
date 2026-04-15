<?php

/*
 * -------------------------------------------------------
 * Domain :: Entidade Category (Categoria)
 * -------------------------------------------------------
 * Entidade pura de domínio. Não conhece frameworks,
 * banco de dados, ou camadas externas.
 *
 * Princípios SOLID aplicados:
 *   - SRP: Responsável apenas por representar e validar
 *          os dados de uma Categoria.
 *   - OCP: Extensível via herança sem modificação.
 */

declare(strict_types=1);

namespace App\Domain\Category;

use InvalidArgumentException;

/**
 * Entidade de domínio que representa uma Categoria de Chamados.
 *
 * Uma Categoria é usada para organizar os Chamados (Tickets)
 * por tipo ou área responsável dentro do sistema.
 */
final class Category
{
    /**
     * Cria uma nova instância da Entidade Category.
     *
     * @param  int|null  $id         Identificador único (nulo para entidades novas)
     * @param  string    $name       Nome descritivo da categoria
     * @param  string    $createdBy  Identificador do autor (usuário/sistema)
     * @param  \DateTimeImmutable|null $createdAt Data de criação
     */
    public function __construct(
        private readonly ?int $id,
        private string $name,
        private readonly string $createdBy,
        private readonly ?\DateTimeImmutable $createdAt = null,
        private readonly int $ticketsCount = 0,
    ) {
        $this->validarNome($name);
    }

    /**
     * Retorna o ID da categoria.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retorna o nome da categoria.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Retorna o identificador do criador da categoria.
     */
    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }

    /**
     * Retorna a data de criação da categoria.
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Retorna a contagem de tickets vinculados.
     */
    public function getTicketsCount(): int
    {
        return $this->ticketsCount;
    }

    /**
     * Atualiza o nome da categoria com validação.
     *
     * @param  string  $novoNome  Novo nome a ser definido
     * @throws InvalidArgumentException se o nome for inválido
     */
    public function renomear(string $novoNome): void
    {
        $this->validarNome($novoNome);
        $this->name = $novoNome;
    }

    /**
     * Valida as regras de negócio para o nome da categoria.
     *
     * @param  string  $nome  Nome a ser validado
     * @throws InvalidArgumentException se o nome não atender às regras
     */
    private function validarNome(string $nome): void
    {
        if (empty(trim($nome))) {
            throw new InvalidArgumentException('O nome da categoria não pode ser vazio.');
        }

        if (strlen($nome) > 255) {
            throw new InvalidArgumentException('O nome da categoria não pode exceder 255 caracteres.');
        }
    }
}
