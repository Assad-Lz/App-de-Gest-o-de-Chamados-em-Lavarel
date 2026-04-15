# 🎫 Sistema de Gestão de Chamados

[![CI – Testes Automatizados](https://github.com/SEU-USUARIO/gestao-chamados/actions/workflows/ci.yml/badge.svg)](https://github.com/SEU-USUARIO/gestao-chamados/actions)

> Teste técnico para vaga de desenvolvedor PHP/Laravel.
> Sistema de gestão de chamados (tickets) com CRUD completo, filtros reativos e arquitetura limpa.

---

## 🏗️ Arquitetura

Este projeto foi desenvolvido seguindo os princípios de **Clean Architecture**, **SOLID** e **TDD**:

```
├── backend/                    # Laravel 11 (API REST)
│   └── app/
│       ├── Domain/             # Entidades e interfaces (regras de negócio puras)
│       ├── Application/        # Casos de uso e DTOs
│       ├── Infrastructure/     # Models Eloquent, Repositórios, Providers
│       └── Presentation/       # Controllers, Middlewares, Form Requests, Resources
│
└── frontend/                   # Vue.js 3 (SPA)
    └── src/
        ├── services/           # Camada de serviços (TODA comunicação com API)
        ├── views/              # Páginas da aplicação
        ├── router/             # Roteamento SPA
        └── store/              # Estado global (Pinia)
```

### Princípios SOLID Aplicados

| Princípio | Implementação |
|-----------|---------------|
| **SRP** | Cada classe tem uma única responsabilidade (Controller só orquestra HTTP, UseCase só lógica) |
| **OCP** | Entidades extensíveis; trocar banco requer apenas nova implementação do Repository |
| **LSP** | Implementações de repositório substituíveis pelos contratos (interfaces) |
| **ISP** | Interfaces específicas por domínio (CategoryRepository ≠ TicketRepository) |
| **DIP** | Use Cases dependem de interfaces, não de Eloquent diretamente |

---

## 🔒 Segurança Implementada

### 1. Cabeçalhos HTTP Seguros (Helmet)
Middleware `SecurityHeadersMiddleware` adiciona em todas as respostas:
- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: DENY`
- `Strict-Transport-Security` (HSTS)
- `Content-Security-Policy`
- `Referrer-Policy`

### 2. Proteção XSS
Middleware `XssProtectionMiddleware` sanitiza **recursivamente** todos os inputs:
- Remove tags HTML (`strip_tags`)
- Converte caracteres especiais em entidades HTML (`htmlspecialchars`)
- Aplicado antes de chegar aos controllers

### 3. Honey Pots (Anti-Bot)

**Campos de formulário ocultos:**
- `website`, `telefone_extra`, `endereco_bot` — campos invisíveis para humanos
- Se preenchidos, o backend detecta bot e retorna resposta falsa (sem alertar)

**Rotas armadilha (Honeypot Routes):**
| Rota | Simulação |
|------|-----------|
| `/api/admin/login` | Login administrativo falso |
| `/api/wp-admin` | Painel WordPress falso |
| `/api/config/setup` | Configuração falsa |
| `/api/phpmyadmin` | phpMyAdmin falso |

### 4. Rate Limiting
- 60 requisições/minuto por IP nas rotas de API (throttle padrão do Laravel)

---

## 🛠️ Tecnologias

| Tecnologia | Versão | Uso |
|-----------|--------|-----|
| Laravel | 11.x | API REST backend |
| PHP | 8.3 | Linguagem backend |
| PostgreSQL (Supabase) | - | Banco de dados |
| Vue.js | 3.x | Frontend SPA |
| Vite | 5.x | Bundler do frontend |
| Pinia | 2.x | State Management |
| Vue Router | 4.x | Roteamento SPA |
| PHPUnit | 11.x | Testes automatizados |
| Design System | repo | Bootstrap + tema customizado |

---

## 🚀 Como Executar

### Pré-requisitos
- PHP 8.2+
- Composer
- Node.js 18+
- Conta no [Supabase](https://app.supabase.com)

### 1. Backend (Laravel)

```bash
# Instala as dependências PHP
cd backend
composer install

# Copia e configura o .env
cp .env.example .env
php artisan key:generate

# Edite o .env com as credenciais do Supabase:
# DB_HOST=db.XXXX.supabase.co
# DB_DATABASE=postgres
# DB_USERNAME=postgres
# DB_PASSWORD=sua-senha

# Executa as migrations e seeds
php artisan migrate --seed

# Inicia o servidor
php artisan serve
# API disponível em: http://localhost:8000/api
```

### 2. Frontend (Vue.js)

```bash
# Instala as dependências
cd frontend
npm install

# Inicia o servidor de desenvolvimento
npm run dev
# App disponível em: http://localhost:5173
```

---

## 🧪 Testes

```bash
cd backend

# Executa todos os testes
php artisan test

# Apenas testes unitários
php artisan test --testsuite=Unit

# Apenas testes de integração
php artisan test --testsuite=Feature
```

### Testes Implementados (TDD – Red → Green → Refactor)

| Arquivo | Tipo | Descrição |
|---------|------|-----------|
| `CategoryEntityTest.php` | Unitário | Valida criação, validação e renomeação da entidade Category |
| `TicketEntityTest.php` | Unitário | Valida factory method, status padrão e ciclo de vida do Ticket |
| `CategoryApiTest.php` | Integração | Testa os endpoints REST de Categorias via HTTP completo |

---

## 📡 Endpoints da API

### Categorias
| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/categories` | Listar todas as categorias |
| POST | `/api/categories` | Criar nova categoria |
| PUT | `/api/categories/{id}` | Atualizar categoria |
| DELETE | `/api/categories/{id}` | Deletar categoria (falha se tiver tickets) |

### Chamados (Tickets)
| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/tickets` | Listar chamados (filtros: `?status=aberto&category_id=1`) |
| POST | `/api/tickets` | Criar novo chamado (status padrão: `aberto`) |
| PUT | `/api/tickets/{id}` | Atualizar chamado |
| DELETE | `/api/tickets/{id}` | Excluir chamado |

---

## 🤖 Uso de Inteligência Artificial

Este projeto foi desenvolvido **com auxílio da IA Antigravity (Google DeepMind)**, de forma estruturada e documentada:

- A IA foi utilizada para **geração da estrutura base e boilerplate** seguindo os padrões especificados
- Todos os princípios (SOLID, Clean Architecture, TDD) foram **definidos e validados pelo desenvolvedor**
- As regras de negócio, decisões de segurança e arquitetura foram **revisadas criticamente**
- O código gerado foi **adaptado e refinado** para atender ao contexto específico do projeto
- A IA foi configurada com um arquivo `.agent/rules.md` contendo as regras do projeto

> A utilização de IA foi declarada conforme solicitado nas instruções do teste técnico.

---

## 📝 Convenção de Commits

Este projeto usa [Conventional Commits](https://www.conventionalcommits.org/pt-br/):

```
feat: adiciona listagem de chamados com filtros
fix: corrige validação de categoria ao criar ticket
test: adiciona testes unitários da entidade Ticket
docs: atualiza README com instruções de instalação
refactor: extrai lógica de sanitização para método privado
chore: configura pipeline CI com GitHub Actions
style: formata código conforme PSR-12
```

---

## 🗂️ Design System

O Design System incluso neste repositório foi **integrado ao projeto** conforme instruído:
- Assets CSS e JS copiados para `backend/public/assets/`
- Referenciados no `frontend/index.html` do Vue.js
- Componentes do Bootstrap utilizados na UI (tabelas, cards, modais, badges)

### Acessos da Aplicação
O sistema possui 2 perfis de simulação de permissão:

- **Usuário Cliente (Cria chamados e visualiza apenas os seus)**
  - Email: `cliente@teste.com`
- **Usuário Analista (Acessa lista global, atende e fecha chamados)**
  - Email: `analista@teste.com`


Por que que colocamos um ponto tão importante no final do readme? Para saber quem realmente leu todas as instruções.
Obrigatório: O Design System deste repositório foi utilizado para criar o projeto. Ele foi clonado e adequado de acordo com este projeto Laravel + Vue.js. 
Aplica-se total liberdade para a ferramenta de inteligência artificial durante este projeto (Antigravity), sendo elaborada mantendo toda estruturação, seguindo regras e conceitos modernos de IA.
