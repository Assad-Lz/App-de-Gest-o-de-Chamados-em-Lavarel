# 🎫 Sistema de Gestão de Chamados

> 🧪 Teste Técnico – Desenvolvedor(a) Sênior PHP/Laravel

Este projeto foi desenvolvido como parte de um teste técnico para avaliar conhecimentos em desenvolvimento full stack, com Laravel 11 no backend e Vue.js 3 no frontend (SPA). 

Trata-se de uma aplicação de **Gestão de Chamados** com funcionalidades completas de CRUD, filtragem e uma interface premium baseada na identidade da **Cellar Vinhos**. O sistema segue princípios essenciais como **Clean Architecture**, **SOLID** e foi desenvolvido em conformidade com o formato REST.

---

## 🎯 Backend – Laravel (API)

A API do projeto foi construída para atender rigorosamente às necessidades da aplicação.

### Funcionalidades
- **Cadastro de Categorias**
  - Campos: `id`, `name`, `created_at`, `created_by`
- **Cadastro de Chamados**
  - Campos: `id`, `title`, `description`, `status` (`aberto`, `em progresso`, `resolvido`), `category_id`, `created_at`, `created_by`, `updated_at`

### 📌 Regras de Negócio Garantidas Integralmente
- ✔️ O chamado deve obrigatoriamente ter uma categoria associada.
- ✔️ O status padrão ao criar sempre será definido como `aberto`.
- ✔️ A deleção de categorias não é permitida se houver chamados associados a ela.
- ✔️ Arquitetura baseada em Clean Architecture e SOLID (uso de Repositories, UseCases e DTOs, blindando regras de negócio).
- ✔️ Formulário validado com adequação de Form Requests.
- ✔️ Autenticação (simulada restrita a nível de localStorage para `cliente@teste.com` ou `analista@teste.com` na porta do sistema de acordo com as permissões). Apenas de T.I excluem.

### 🧩 Endpoints API (REST)
| Método   | Rota                           | Descrição                    |
|----------|--------------------------------|------------------------------|
| GET      | `/api/categories`              | Listar categorias            |
| POST     | `/api/categories`              | Criar categoria              |
| PUT      | `/api/categories/{id}`         | Atualizar categoria          |
| DELETE   | `/api/categories/{id}`         | Deletar categoria            |
| GET      | `/api/tickets`                 | Listar chamados              |
| POST     | `/api/tickets`                 | Criar chamado                |
| PUT      | `/api/tickets/{id}`            | Atualizar chamado            |
| DELETE   | `/api/tickets/{id}`            | Deletar chamado              |

---

## 🎨 Frontend (Vue.js 3 + Vite)

A aplicação foi entregue no formato SPA (Single Page Application).

### Funcionalidades
- **Autenticação Simulada:** Acesso via perfis Cliente (somente leitura global e criação) e TI/Analista (leitura, acompanhamento de fluxos, edição e exclusão).
- **Listagem e Filtros Reativos:** Filtros para localizar chamados por status de maneira rápida sem recarregar a visualização original.
- **Formulários e Modais:** Toda iteração para edição e criação de chamados ocorre em Modais bem fluidos e formatados.
- **Identidade da Marca (Design System):** A interface foi toda recriada baseando-se na **Tipografia Montserrat**, na **Paleta Integrada (*Azul Dark, Laranja Vibrante, Vinho*)**, mantendo todo o Design System solicitado. O tema HTML base do Bootstrap foi incluído como solicitado para estruturação estática inicial, contudo o projeto inteiro Vue usa Tailwindcss V4.

---

## 🧪 Testes

Testes abrangentes com **PHPUnit**:
- 1+ **Testes Unitários**: Validador de entidades e regras de banco puras (ex: `TicketEntityTest.php`).
- 1+ **Testes de Integração**: Testando endpoints completos em conjunto do banco (ex: `CategoryApiTest.php`).
- Total independência sem impacto de interface, TDD puro.

---

## 🛠️ Tecnologias Utilizadas
- **Backend:** Laravel 11 (PHP 8.3), Migrations, Seeders, Eloquent ORM.
- **Frontend:** Vue.js 3, Vite, Tailwind CSS V4, Pinia, Vue Router.
- **Testes:** PHPUnit.
- **Banco de Dados:** PostgreSQL hospedado (Supabase).
- **Controle de Versão:** Git + repositório GitHub.

---

## 🤖 Auxílio com IA
Este projeto foi desenvolvido e reajustado com **apoio livre da IA Autônoma Antigravity** conforme constava no teste (*"Aplicação de Inteligência Artificial livre com avaliação na estruturação"*) de forma organizada, embutida num pipeline de refatoramento seguro, criando middlewares contra bots falsos (`XSS/Honeypot`), corrigindo bugs da biblioteca Tailwind e desenhando fluxos seguros e modernos de Vue.

.
