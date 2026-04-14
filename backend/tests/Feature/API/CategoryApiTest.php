<?php

/*
 * -------------------------------------------------------
 * Tests :: Feature – API Categories (Teste de Integração)
 * -------------------------------------------------------
 * Testes de integração para os endpoints da API de Categorias.
 * Usam banco de dados real (SQLite em memória para testes) e
 * fazem requisições HTTP completas para validar o comportamento.
 *
 * Abordagem TDD: estes testes foram escritos antes da implementação
 * seguindo o ciclo Red → Green → Refactor.
 */

declare(strict_types=1);

namespace Tests\Feature\API;

use App\Infrastructure\Models\CategoryModel;
use App\Infrastructure\Models\TicketModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testes de integração para a API de Categorias.
 *
 * Valida os endpoints CRUD e as regras de negócio via HTTP,
 * incluindo a regra de não poder deletar categoria com tickets.
 */
class CategoryApiTest extends TestCase
{
    /**
     * Reseta o banco de dados entre cada teste para isolar os casos.
     */
    use RefreshDatabase;

    /**
     * Teste de Integração 1: GET /api/categories deve retornar lista vazia inicialmente.
     *
     * @test
     */
    public function deve_retornar_lista_vazia_de_categorias(): void
    {
        // Act – faz a requisição HTTP GET
        $response = $this->getJson('/api/categories');

        // Assert – verifica a resposta
        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    /**
     * Teste de Integração 2: POST /api/categories deve criar uma nova categoria.
     *
     * @test
     */
    public function deve_criar_categoria_via_api(): void
    {
        // Arrange – dados da nova categoria
        $dados = ['name' => 'Suporte Técnico'];

        // Act – requisição POST
        $response = $this->postJson('/api/categories', $dados);

        // Assert – categoria criada com status 201
        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Suporte Técnico');

        // Verifica se foi persistida no banco
        $this->assertDatabaseHas('categories', ['name' => 'Suporte Técnico']);
    }

    /**
     * Teste de Integração 3: DELETE /api/categories/{id} deve falhar
     * se houver chamados vinculados (regra de negócio obrigatória).
     *
     * @test
     */
    public function deve_rejeitar_deletar_categoria_com_tickets(): void
    {
        // Arrange – cria categoria e ticket associado
        $categoria = CategoryModel::create([
            'name'       => 'Infraestrutura',
            'created_by' => 'teste',
        ]);

        TicketModel::create([
            'title'       => 'Chamado de teste',
            'description' => 'Descrição do chamado de teste.',
            'status'      => 'aberto',
            'category_id' => $categoria->id,
            'created_by'  => 'teste',
        ]);

        // Act – tenta deletar a categoria que tem ticket
        $response = $this->deleteJson("/api/categories/{$categoria->id}");

        // Assert – deve retornar 422 (regra de negócio violada)
        $response->assertStatus(422)
            ->assertJsonPath('message', fn($msg) => str_contains($msg, 'chamados associados'));

        // A categoria ainda deve existir no banco
        $this->assertDatabaseHas('categories', ['id' => $categoria->id]);
    }

    /**
     * Teste de Integração 4: PUT /api/categories/{id} deve atualizar a categoria.
     *
     * @test
     */
    public function deve_atualizar_categoria_via_api(): void
    {
        // Arrange – cria a categoria original
        $categoria = CategoryModel::create([
            'name'       => 'Nome Antigo',
            'created_by' => 'teste',
        ]);

        // Act – atualiza via API
        $response = $this->putJson("/api/categories/{$categoria->id}", [
            'name' => 'Nome Atualizado',
        ]);

        // Assert – nome atualizado com sucesso
        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Nome Atualizado');

        $this->assertDatabaseHas('categories', ['name' => 'Nome Atualizado']);
        $this->assertDatabaseMissing('categories', ['name' => 'Nome Antigo']);
    }
}
