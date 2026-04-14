# 🤖 Regras do Agente – Gestão de Chamados

## Contexto do Projeto

Sistema de **Gestão de Chamados** desenvolvido como teste técnico para vaga de estágio PHP/Laravel.
O repositório clonado contém o **Design System** oficial que DEVE ser utilizado no frontend.

## Stack Tecnológica

- **Backend**: Laravel 10+ (PHP 8.3)
- **Banco de Dados**: Supabase (PostgreSQL) via Laravel Eloquent ORM com driver pgsql
- **Frontend**: Vue.js 3 (SPA) + Design System do repositório (Bootstrap + assets customizados)
- **Testes**: PHPUnit (4 testes unitários + testes de integração)
- **Segurança**: Helmet (cabeçalhos HTTP), proteção XSS, Honey Pots, CSRF

## @rules – Regras Obrigatórias

1. **Conventional Commits**: Todo commit deve seguir o padrão:
   - `feat:`, `fix:`, `test:`, `docs:`, `refactor:`, `chore:`, `style:`
2. **Comentários em Português**: Todo comentário no código deve ser em pt-BR, claro e objetivo
3. **Arquitetura TDD**: Escrever testes ANTES da implementação (Red → Green → Refactor)
4. **SOLID**: Aplicar todos os 5 princípios em cada componente
5. **Clean Architecture**: Separar em camadas (Domain, Application, Infrastructure, Presentation)
6. **Clean Code**: Nomes expressivos, funções pequenas, sem duplicação
7. **Frontend Intuitivo**: UX/UI limpa, bonita, responsiva, usando o Design System do repo
8. **Nenhuma requisição direta no frontend**: Toda chamada API passa por middlewares/services
9. **Middlewares**: Criar middlewares para segurança, logging, rate limiting e honeypots
10. **Sistema escalável e seguro**: Rate limiting, sanitização de inputs, validação robusta

## Estrutura de Diretórios (Clean Architecture)

```
backend/
├── app/
│   ├── Domain/              # Entidades e regras de negócio puras
│   │   ├── Category/
│   │   └── Ticket/
│   ├── Application/         # Casos de uso (Use Cases)
│   │   ├── Category/
│   │   └── Ticket/
│   ├── Infrastructure/      # Implementações concretas (DB, external)
│   │   ├── Repositories/
│   │   └── Models/
│   └── Presentation/        # Controllers, Requests, Resources
│       ├── Http/
│       └── Middlewares/
frontend/                    # Vue.js SPA
├── src/
│   ├── services/            # Camada de serviços (toda comunicação com API)
│   ├── components/
│   ├── views/
│   └── store/
```

## Endpoints da API

| Método | Rota                   | Descrição                     |
|--------|------------------------|-------------------------------|
| GET    | /api/categories        | Listar categorias             |
| POST   | /api/categories        | Criar categoria               |
| PUT    | /api/categories/{id}   | Atualizar categoria           |
| DELETE | /api/categories/{id}   | Deletar categoria             |
| GET    | /api/tickets           | Listar chamados (com filtros) |
| POST   | /api/tickets           | Criar chamado                 |
| PUT    | /api/tickets/{id}      | Atualizar chamado             |
| DELETE | /api/tickets/{id}      | Deletar chamado               |

## Regras de Negócio

- Chamado DEVE ter categoria associada (obrigatório)
- Status padrão ao criar: `aberto`
- Deletar categoria: proibido se houver chamados vinculados
- Status possíveis: `aberto`, `em_progresso`, `resolvido`

## Honey Pots Implementados

1. **Campo oculto `website`**: Campo invisível no formulário; se preenchido, requisição é bloqueada
2. **Rota armadilha `/admin/login`**: Qualquer acesso é logado como potencial ataque
3. **Campo `telefone_extra`**: Campo invisible no form; bots preenchem campos ocultos
4. **Header de detecção**: X-Requested-With ausente = suspeita de bot

## Segurança

- **XSS**: Sanitização via `htmlspecialchars`, `strip_tags`, middleware dedicado
- **CSRF**: Token em toda requisição não-GET via Laravel Sanctum
- **Helmet (cabeçalhos)**: CSP, X-Frame-Options, HSTS, X-Content-Type via middleware
- **Rate Limiting**: 60 req/min por IP nas rotas de API
- **SQL Injection**: Prevenido pelo Eloquent ORM (prepared statements)
- **Honey Pots**: 3 armadilhas para detectar bots/scanners

## Design System

O repositório já contém o Design System com:
- `assets/css/app.min.css` - CSS principal do tema
- `assets/css/bootstrap.min.css` - Bootstrap customizado
- `assets/css/icons.min.css` - Ícones
- `assets/js/` - Scripts do tema

Estes arquivos DEVEM ser copiados para `public/` do Laravel e referenciados no Vue.js.

## GitHub

- Repositório: a ser criado pelo usuário em github.com/[seu-usuario]/gestao-chamados
- Branch padrão: `main`
- Commits: seguir Conventional Commits
