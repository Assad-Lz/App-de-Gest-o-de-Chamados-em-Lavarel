<?php

declare(strict_types=1);

namespace Tests\Feature\Security;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Category\Category;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste 1: Garantir que nenhum Script Malicioso (XSS) seja aceito no sistema.
     * Verifica se o middleware XssProtection está sanitizando os inputs.
     */
    public function test_api_sanitizes_xss_payloads(): void
    {
        // Criar categoria via Eloquent para o teste
        $category = new \App\Infrastructure\Models\CategoryModel();
        $category->name = 'TI';
        $category->save();

        $payload = [
            'title' => 'Ticket com script <script>alert("xss")</script>',
            'description' => '<img src=x onerror=alert(1)> Descrição maliciosa',
            'categoryId' => $category->id,
            'createdBy' => 'user@test.com',
            'userName' => 'Hacker',
            'userEmail' => 'hacker@test.com',
            'department' => 'IT'
        ];

        // Tenta enviar para a rota de criação de tickets
        $response = $this->postJson('/api/v1/tickets', $payload);

        $response->assertStatus(201);
        
        $data = $response->json();
        
        // O middleware deve ter removido as tags <script> e <img> ou convertido elas
        $this->assertStringNotContainsString('<script>', $data['data']['title']);
        $this->assertStringNotContainsString('onerror', $data['data']['description']);
        // O htmlspecialchars converte < em &lt; etc.
        $this->assertStringContainsString('alert(&quot;xss&quot;)', $data['data']['title']);
    }

    /**
     * Teste 2: Garantir que o backend esteja "oculto" e protegido contra scanners.
     * Verifica se os cabeçalhos de segurança estão presentes e se o Server/X-Powered-By está oculto.
     */
    public function test_api_hides_backend_identity_and_uses_security_headers(): void
    {
        $response = $this->getJson('/api/v1/categories');

        // Verifica cabeçalhos de segurança (Helmet/SecurityHeadersMiddleware)
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('Content-Security-Policy');

        // Garante que tecnologia não é exposta
        $this->assertFalse($response->headers->has('X-Powered-By'));
        $this->assertFalse($response->headers->has('Server'));
        
        // Verifica se rotas administrativas padrão estão bloqueadas pelo Honeypot
        $honeypotResponse = $this->get('/wp-admin');
        $honeypotResponse->assertStatus(401);
    }

    /**
     * Teste 3: Garantir que nenhuma requisição de scanner seja vista/aceita (Backend Obfuscation)
     */
    public function test_api_blocks_common_scanners(): void
    {
        // Simula o User-Agent do Nmap ou scanner
        $response = $this->withHeaders([
            'User-Agent' => 'Nmap Scripting Engine'
        ])->getJson('/api/v1/categories');

        // O BackendObfuscationMiddleware deve retornar 503 para scanners
        $response->assertStatus(503);
    }
}
