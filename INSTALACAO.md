# 🚀 Guia de Instalação e Configuração

## Pré-requisitos

Antes de executar o projeto, instale:

### PHP 8.3 + Extensões
```bash
sudo apt-get update
sudo apt-get install -y php8.3 php8.3-cli php8.3-fpm \
  php8.3-mbstring php8.3-xml php8.3-curl php8.3-pgsql \
  php8.3-zip php8.3-bcmath php8.3-intl php8.3-tokenizer \
  php8.3-gd unzip curl
```

### Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

---

## 1. Configurar o Backend (Laravel)

```bash
cd backend

# Instalar dependências PHP
composer install

# Copiar e configurar o arquivo de ambiente
cp .env.example .env
php artisan key:generate

# ⚠️ OBRIGATÓRIO: editar o .env com suas credenciais do Supabase
# Acesse: https://app.supabase.com → seu projeto → Settings → Database
# Copie a "Connection string" e preencha:
nano .env
```

**Variáveis a preencher no `.env`:**
```env
DB_HOST=db.XXXXXXXXXXXXXX.supabase.co
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=sua-senha-do-supabase
```

```bash
# Executar migrations (cria tabelas no Supabase)
php artisan migrate

# Popular banco com dados de demonstração
php artisan migrate --seed

# Copiar Design System para pasta pública
cp -r ../assets ./public/

# Iniciar servidor
php artisan serve
# ✅ API rodando em: http://localhost:8000/api
```

---

## 2. Configurar o Frontend (Vue.js)

```bash
cd frontend

# Instalar dependências Node.js
npm install

# Iniciar servidor de desenvolvimento
npm run dev
# ✅ Frontend rodando em: http://localhost:5173
```

---

## 3. Conectar ao GitHub

### a) Crie um repositório no GitHub
1. Acesse https://github.com/new
2. Nome: `gestao-chamados`
3. Deixe **privado ou público** conforme preferir
4. **NÃO** inicialize com README (já temos um)
5. Clique em "Create repository"

### b) Conecte o projeto local ao GitHub
```bash
# Na raiz do projeto (onde está o .git)
cd "/home/zacky/Área de Trabalho/Github_Projects/teste-laravel-dev-sr"

# Adicione o remote do GitHub (substitua SEU-USUARIO)
git remote add origin https://github.com/SEU-USUARIO/gestao-chamados.git

# Renomeie para main (se necessário)
git branch -M main

# Envie o código para o GitHub
git push -u origin main
```

---

## 4. Executar os Testes

```bash
cd backend

# Todos os testes
php artisan test

# Apenas unitários (sem banco de dados)
php artisan test --testsuite=Unit

# Apenas integração (requer banco ou SQLite)
php artisan test --testsuite=Feature

# Com cobertura de código (requer Xdebug)
php artisan test --coverage
```

---

## 5. Verificar os Honeypots

Os honeypots estão ativos e logam em `backend/storage/logs/`.

Para testar:
```bash
# Tenta acessar rota armadilha – deve retornar 401 convincente
curl http://localhost:8000/api/wp-admin

# Verifica os logs de segurança
tail -f backend/storage/logs/laravel.log
```

---

## Estrutura de URLs

| URL | Descrição |
|-----|-----------|
| `http://localhost:5173` | Frontend Vue.js (SPA) |
| `http://localhost:5173/chamados` | Página de Chamados |
| `http://localhost:5173/categorias` | Página de Categorias |
| `http://localhost:8000/api/categories` | API de Categorias |
| `http://localhost:8000/api/tickets` | API de Tickets |
| `http://localhost:8000/api/wp-admin` | Honeypot 🍯 |
