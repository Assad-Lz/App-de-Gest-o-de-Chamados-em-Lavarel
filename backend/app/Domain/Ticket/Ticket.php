<?php

/*
 * -------------------------------------------------------
 * Domain :: Entidade Ticket (Chamado)
 * -------------------------------------------------------
 * Entidade pura de domínio. Representa um Chamado no sistema
 * de gestão. Contém as regras de negócio relacionadas ao
 * ciclo de vida e validação de um chamado.
 *
 * Princípios SOLID aplicados:
 *   - SRP: Responsável somente pelos dados e validações do Chamado
 *   - OCP: Extensor via herança sem modificação direta
 */

declare(strict_types=1);

namespace App\Domain\Ticket;

use InvalidArgumentException;

/**
 * Entidade de domínio que representa um Chamado (Ticket).
 *
 * Um Chamado é vinculado a uma Categoria e possui um ciclo
 * de vida: aberto → em_progresso → resolvido.
 */
final class Ticket
{
    /**
     * Status possíveis para um chamado.
     * Seguem o padrão definido na spec do teste técnico.
     */
    public const STATUS_ABERTO       = 'aberto';
    public const STATUS_EM_PROGRESSO = 'em_progresso';
    public const STATUS_RESOLVIDO    = 'resolvido';

    /** Lista de status válidos para validação */
    private const STATUS_VALIDOS = [
        self::STATUS_ABERTO,
        self::STATUS_EM_PROGRESSO,
        self::STATUS_RESOLVIDO,
    ];

    /**
     * Cria uma nova instância da Entidade Ticket.
     *
     * @param  int|null    $id          Identificador único (nulo para tickets novos)
     * @param  string      $title       Título do chamado
     * @param  string      $description Descrição detalhada do problema
     * @param  string      $status      Status atual do chamado
     * @param  int         $categoryId  ID da categoria associada (obrigatório)
     * @param  string      $createdBy   Identificador do autor
     * @param  \DateTimeImmutable|null $createdAt Data de criação
     */
    public function __construct(
        private readonly ?int $id,
        private string $title,
        private string $description,
        private string $status,
        private int $categoryId,
        private readonly string $createdBy,
        private readonly ?\DateTimeImmutable $createdAt = null,
    ) {
        $this->validarTitulo($title);
        $this->validarStatus($status);
    }

    /**
     * Cria um novo Ticket com status padrão "aberto".
     * Factory Method que encapsula a regra de negócio do status inicial.
     *
     * @param  string  $title       Título do chamado
     * @param  string  $description Descrição do chamado
     * @param  int     $categoryId  ID da categoria
     * @param  string  $createdBy   Autor do chamado
     * @return self Novo ticket com status "aberto"
     */
    public static function criar(
        string $title,
        string $description,
        int $categoryId,
        string $createdBy,
    ): self {
        return new self(
            id: null,
            title: $title,
            description: $description,
            status: self::STATUS_ABERTO, // Regra de negócio: status padrão é "aberto"
            categoryId: $categoryId,
            createdBy: $createdBy,
        );
    }

    /**
     * Retorna o ID do ticket.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retorna o título do chamado.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Retorna a descrição do chamado.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Retorna o status atual do chamado.
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Retorna o ID da categoria vinculada ao chamado.
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * Retorna o identificador do criador do chamado.
     */
    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }

    /**
     * Retorna a data de criação do chamado.
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Atualiza o status do chamado com validação.
     *
     * @param  string  $novoStatus  Novo status a ser aplicado
     * @throws InvalidArgumentException se o status for inválido
     */
    public function alterarStatus(string $novoStatus): void
    {
        $this->validarStatus($novoStatus);
        $this->status = $novoStatus;
    }

    /**
     * Atualiza os dados editáveis do chamado.
     *
     * @param  string  $titulo      Novo título
     * @param  string  $descricao   Nova descrição
     * @param  string  $status      Novo status
     * @param  int     $categoryId  Novo ID de categoria
     */
    public function atualizar(string $titulo, string $descricao, string $status, int $categoryId): void
    {
        $this->validarTitulo($titulo);
        $this->validarStatus($status);

        $this->title       = $titulo;
        $this->description = $descricao;
        $this->status      = $status;
        $this->categoryId  = $categoryId;
    }

    /**
     * Verifica se o chamado está resolvido.
     */
    public function estaResolvido(): bool
    {
        return $this->status === self::STATUS_RESOLVIDO;
    }

    /**
     * Retorna a lista de status válidos para uso externo.
     *
     * @return string[]
     */
    public static function getStatusValidos(): array
    {
        return self::STATUS_VALIDOS;
    }

    /**
     * Valida as regras de negócio para o título do chamado.
     *
     * @param  string  $titulo  Título a ser validado
     * @throws InvalidArgumentException se inválido
     */
    private function validarTitulo(string $titulo): void
    {
        if (empty(trim($titulo))) {
            throw new InvalidArgumentException('O título do chamado não pode ser vazio.');
        }

        if (strlen($titulo) > 255) {
            throw new InvalidArgumentException('O título do chamado não pode exceder 255 caracteres.');
        }
    }

    /**
     * Valida se o status fornecido é um dos status permitidos.
     *
     * @param  string  $status  Status a ser validado
     * @throws InvalidArgumentException se o status não for reconhecido
     */
    private function validarStatus(string $status): void
    {
        if (!in_array($status, self::STATUS_VALIDOS, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Status "%s" inválido. Os status permitidos são: %s.',
                    $status,
                    implode(', ', self::STATUS_VALIDOS)
                )
            );
        }
    }
}
