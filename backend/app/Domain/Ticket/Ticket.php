<?php

/*
 * -------------------------------------------------------
 * Domain :: Entidade Ticket (Chamado)
 * -------------------------------------------------------
 * Entidade pura de domínio. Representa um Chamado no sistema
 * de gestão. Contém as regras de negócio relacionadas ao
 * ciclo de vida e validação de um chamado.
 */

declare(strict_types=1);

namespace App\Domain\Ticket;

use InvalidArgumentException;

final class Ticket
{
    public const STATUS_ABERTO       = 'aberto';
    public const STATUS_EM_PROGRESSO = 'em_progresso';
    public const STATUS_RESOLVIDO    = 'resolvido';

    private const STATUS_VALIDOS = [
        self::STATUS_ABERTO,
        self::STATUS_EM_PROGRESSO,
        self::STATUS_RESOLVIDO,
    ];

    public function __construct(
        private readonly ?int $id,
        private string $title,
        private string $description,
        private string $status,
        private int $categoryId,
        private readonly string $createdBy,
        private readonly ?string $userName = null,
        private readonly ?string $userEmail = null,
        private readonly ?string $department = null,
        private readonly ?string $ticketNumber = null,
        private readonly ?\DateTimeImmutable $createdAt = null,
        private ?\DateTimeImmutable $updatedAt = null,
        private readonly array $comments = [],
    ) {
        $this->validarTitulo($title);
        $this->validarStatus($status);
    }

    public static function criar(
        string $title,
        string $description,
        int $categoryId,
        string $createdBy,
        ?string $userName = null,
        ?string $userEmail = null,
        ?string $department = null,
    ): self {
        return new self(
            id: null,
            title: $title,
            description: $description,
            status: self::STATUS_ABERTO,
            categoryId: $categoryId,
            createdBy: $createdBy,
            userName: $userName,
            userEmail: $userEmail,
            department: $department,
            comments: [],
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicketNumber(): ?string
    {
        return $this->ticketNumber;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function getComments(): array
    {
        return $this->comments;
    }

    public function alterarStatus(string $novoStatus): void
    {
        $this->validarStatus($novoStatus);
        $this->status = $novoStatus;
    }

    public function atualizar(string $titulo, string $descricao, string $status, int $categoryId): void
    {
        $this->validarTitulo($titulo);
        $this->validarStatus($status);

        $this->title       = $titulo;
        $this->description = $descricao;
        $this->status      = $status;
        $this->categoryId  = $categoryId;
        $this->updatedAt   = new \DateTimeImmutable();
    }

    public function estaResolvido(): bool
    {
        return $this->status === self::STATUS_RESOLVIDO;
    }

    public static function getStatusValidos(): array
    {
        return self::STATUS_VALIDOS;
    }

    private function validarTitulo(string $titulo): void
    {
        if (empty(trim($titulo))) {
            throw new InvalidArgumentException('O título do chamado não pode ser vazio.');
        }

        if (strlen($titulo) > 255) {
            throw new InvalidArgumentException('O título do chamado não pode exceder 255 caracteres.');
        }
    }

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
