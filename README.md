# 🍷 Cellar Vinhos - Gestão de Chamados (TI)

Sistema premium de gestão de chamados desenvolvido para a **Cellar Vinhos**, focado em eficiência operacional, comunicação clara e design de alta fidelidade.

## 🖼️ Visual do Projeto (Screenshots)

### 📊 Painel do Analista
![Dashboard Analista](./frontend/public/assets/images/dashboard_analista.png)

### 📋 Listagem e Gestão de Chamados
![Gerenciamento de Chamados](./frontend/public/assets/images/gerenciamento_chamados_lista.png)

### 💬 Comunicação e Follow-up (Messenger)
![Follow-up System](./frontend/public/assets/images/visao_geral_followup.png)

### 📁 Gestão de Categorias
![Categorias](./frontend/public/assets/images/gerenciamento_categorias.png)

### 🏠 Área do Cliente
![Dashboard Cliente](./frontend/public/assets/images/dashboard_cliente.png)

## 🚀 Funcionalidades Principais

### Para o Analista (T.I)
- **Dashboard Estratégico:** KPIs em tempo real (Total, Aguardando, Em Progresso, Resolvidos) com gráficos de eficiência.
- **Busca Avançada (Lupa):** Encontre qualquer ticket instantaneamente por ID, Protocolo (TK-2026...), Nome do Requisitante ou E-mail.
- **Gestão de Categorias:** Controle total sobre as categorias de atendimento.
- **Ações em Massa:** Exclusão múltipla de chamados para limpeza de base.
- **Sistema de Follow-up:** Chat interno dentro de cada chamado para comunicação direta com o cliente.

### Para o Cliente (Colaborador)
- **Interface Intuitiva:** Abertura simplificada de chamados com campos de Setor e Identificação.
- **Acompanhamento de Status:** Visualização clara do ciclo de vida do chamado.
- **Histórico de Comunicação:** Veja cronologicamente todas as interações do suporte no seu chamado.

## 🛠️ Stack Tecnológica

- **Backend:** Laravel 11 + Clean Architecture + PostgreSQL (Supabase).
- **Frontend:** Vue.js 3 + Vite + Tailwind CSS (Aesthetics v4).
- **Icons & Design:** Lucide Icons + HeroIcons + Custom Glassmorphism UI.
- **Docs:** OpenAPI/Swagger (disponível em `/backend/openapi.yml`).

## 📦 Instalação e Execução

### 1. Backend
```bash
cd backend
composer install
cp .env.example .env
php artisan migrate
php artisan serve
```

### 2. Frontend
```bash
cd frontend
npm install
npm run dev
```

## 📐 Decisões de Arquitetura (Clean Architecture)
O projeto segue princípios de DDD e Arquitetura Limpa:
- **Domain:** Entidades puras e lógica de negócio central.
- **Application:** Casos de uso (UseCases) que orquestram o fluxo de dados.
- **Infrastructure:** Repositórios Eloquent e integrações externas.
- **Presentation:** Controladores de API e Resources para entrega de dados.

## 📈 Melhorias UX/UI Implementadas
1. **Scrolling de Modais:** Modais inteligentes que se adaptam à altura da tela sem "grudar" nas bordas.
2. **Skeleton Loaders:** Feedback visual durante o carregamento de dados (fim do "flash" branco).
3. **Relative Time (FormatDate):** Datas amigáveis como "há 2 horas" para maior agilidade na leitura.
4. **Empty States Ilustrados:** Mensagens claras quando não há dados para exibir.
5. **Interactive Dashboard:** Cards com efeitos de escala, sombras dinâmicas e transições de cor no hover.
6. **Filtros Reativos:** Mudança de status instantânea com contador de itens em cada filtro.
7. **Cross-Origin Resiliency:** Configuração de CORS expandida para suportar múltiplos ambientes de dev.
8. **Feedback de Operação:** Toasts de sucesso/erro integrados em todas as ações críticas.
9. **UI Glassmorphism:** Uso de desfoque de fundo (backdrop-blur) em modais e navegação para profundidade.
10. **Mobile First:** Interface totalmente responsiva para tablets e smartphones.

## 🤖 Desenvolvimento e Configurações de Agente

Este projeto foi desenvolvido utilizando a **IDE Antigravity**, integrando capacidades avançadas de codificação assistida por IA.
- **Agent Rules:** O repositório contém diretrizes específicas para agentes de IA (localizadas em `.agents/` ou `.cursorrules`), garantindo que a manutenção do código siga padrões rigorosos de arquitetura limpa e estética visual.
- **Workflow Assistido:** Todo o processo de design, refatoração de UX e documentação OpenAPI foi orquestrado via Antigravity, maximizando a produtividade e qualidade do entregável.

---
**Desenvolvido para o Teste Técnico - Desenvolvedor Sênior PHP/Laravel.**
