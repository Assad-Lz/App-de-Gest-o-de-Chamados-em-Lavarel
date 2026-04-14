<?php

/*
 * -------------------------------------------------------
 * Tests :: Unit – Entidade Category
 * -------------------------------------------------------
 * Testes unitários para a entidade de domínio Category.
 * Seguem a abordagem TDD: Red → Green → Refactor.
 *
 * Estes testes validam que as regras de negócio do domínio
 * funcionam corretamente de forma isolada, sem banco de dados.
 */

declare(strict_types=1);

namespace Tests\Unit\Category;

use App\Domain\Category\Category;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Testes unitários da entidade Category.
 *
 * Valida as regras de negócio puras do domínio Category:
 *   - Criação com dados válidos
 *   - Rejeição de dados inválidos
 *   - Comportamento do método renomear()
 */
class CategoryEntityTest extends TestCase
{
    /**
     * Teste 1: Deve criar uma categoria válida com todos os dados corretos.
     *
     * @test
     */
    public function deve_criar_categoria_com_dados_validos(): void
    {
        // Arrange – prepara os dados
        $nome      = 'Suporte Técnico';
        $criadoPor = 'sistema';
        $criadoEm  = new \DateTimeImmutable('2024-01-15 10:30:00');

        // Act – executa a ação testada
        $category = new Category(
            id: 1,
            name: $nome,
            createdBy: $criadoPor,
            createdAt: $criadoEm,
        );

        // Assert – verifica o resultado esperado
        $this->assertSame(1, $category->getId());
        $this->assertSame($nome, $category->getName());
        $this->assertSame($criadoPor, $category->getCreatedBy());
        $this->assertSame($criadoEm, $category->getCreatedAt());
    }

    /**
     * Teste 2: Deve lançar exceção quando o nome da categoria for vazio.
     * Valida a regra de negócio de nome obrigatório.
     *
     * @test
     */
    public function deve_rejeitar_nome_vazio_na_criacao(): void
    {
        // Assert – define que uma exceção é esperada
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O nome da categoria não pode ser vazio.');

        // Act – tenta criar categoria com nome vazio (deve lançar exceção)
        new Category(
            id: null,
            name: '',       // Nome inválido
            createdBy: 'sistema',
        );
    }

    /**
     * Teste 3: Deve lançar exceção quando o nome exceder 255 caracteres.
     *
     * @test
     */
    public function deve_rejeitar_nome_com_mais_de_255_caracteres(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O nome da categoria não pode exceder 255 caracteres.');

        // Cria string de 256 caracteres (limite é 255)
        $nomeInvalido = str_repeat('A', 256);

        new Category(
            id: null,
            name: $nomeInvalido,
            createdBy: 'sistema',
        );
    }

    /**
     * Teste 4: O método renomear() deve atualizar o nome com sucesso.
     *
     * @test
     */
    public function deve_renomear_categoria_com_nome_valido(): void
    {
        // Arrange – cria uma categoria inicial
        $category = new Category(
            id: 1,
            name: 'Nome Antigo',
            createdBy: 'sistema',
        );

        // Act – renomeia
        $category->renomear('Nome Novo');

        // Assert – verifica que o nome foi atualizado
        $this->assertSame('Nome Novo', $category->getName());
    }
}
