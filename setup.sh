#!/bin/bash
# ==========================================================
# Script de configuração inicial do projeto Gestão de Chamados
# Autor: Gerado por IA assistente (Antigravity/Gemini)
# Data: 2026-04-14
# ==========================================================

set -e

echo "🚀 Iniciando configuração do projeto Gestão de Chamados..."

# --------------------------------------------------------
# Passo 1: Instalar PHP 8.3 e extensões necessárias
# --------------------------------------------------------
echo "📦 Instalando PHP 8.3..."
sudo apt-get update -qq
sudo apt-get install -y \
  php8.3 php8.3-cli php8.3-fpm php8.3-common \
  php8.3-mbstring php8.3-xml php8.3-curl \
  php8.3-pgsql php8.3-zip php8.3-bcmath \
  php8.3-tokenizer php8.3-gd php8.3-intl \
  unzip curl git 2>&1 | grep -E "(Setting up|already|error)" || true

# --------------------------------------------------------
# Passo 2: Instalar Composer
# --------------------------------------------------------
echo "📦 Instalando Composer..."
if ! command -v composer &> /dev/null; then
  curl -sS https://getcomposer.org/installer | php
  sudo mv composer.phar /usr/local/bin/composer
  sudo chmod +x /usr/local/bin/composer
fi

echo "✅ PHP: $(php -r 'echo phpversion();')"
echo "✅ Composer: $(composer --version --no-ansi 2>&1 | head -1)"

# --------------------------------------------------------
# Passo 3: Criar projeto Laravel dentro de ./backend
# --------------------------------------------------------
echo "🏗️  Criando projeto Laravel 11..."
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

if [ ! -d "$SCRIPT_DIR/backend" ]; then
  composer create-project laravel/laravel backend --prefer-dist 2>&1 | tail -10
  echo "✅ Projeto Laravel criado em ./backend"
else
  echo "⚠️  Diretório ./backend já existe, pulando criação..."
fi

cd "$SCRIPT_DIR/backend"

# --------------------------------------------------------
# Passo 4: Instalar dependências extras do Laravel
# --------------------------------------------------------
echo "📦 Instalando dependências do Laravel..."
composer require \
  laravel/sanctum \
  spatie/laravel-query-builder \
  2>&1 | tail -5

# --------------------------------------------------------
# Passo 5: Copiar assets do Design System para public/
# --------------------------------------------------------
echo "🎨 Copiando Design System para public/..."
cp -r "$SCRIPT_DIR/assets" "$SCRIPT_DIR/backend/public/"
echo "✅ Assets copiados para public/assets/"

# --------------------------------------------------------
# Passo 6: Configurar frontend Vue.js
# --------------------------------------------------------
echo "🖼️  Configurando frontend Vue.js..."
cd "$SCRIPT_DIR"

if [ ! -d "$SCRIPT_DIR/frontend" ]; then
  npm create vite@latest frontend -- --template vue 2>&1 | tail -5
  cd "$SCRIPT_DIR/frontend"
  npm install
  npm install axios vue-router@4 pinia 2>&1 | tail -5
  echo "✅ Frontend Vue.js configurado"
else
  echo "⚠️  Diretório ./frontend já existe, pulando criação..."
fi

# --------------------------------------------------------
# Passo 7: Configurar .env do Laravel
# --------------------------------------------------------
echo "⚙️  Configurando .env do Laravel..."
cd "$SCRIPT_DIR/backend"

if [ ! -f ".env" ]; then
  cp .env.example .env
fi

php artisan key:generate

echo ""
echo "=============================================="
echo "✅ Setup concluído!"
echo "=============================================="
echo ""
echo "📋 Próximos passos:"
echo "  1. Configure as variáveis do Supabase no backend/.env:"
echo "     DB_CONNECTION=pgsql"
echo "     DB_HOST=db.XXXXXXXX.supabase.co"
echo "     DB_PORT=5432"
echo "     DB_DATABASE=postgres"
echo "     DB_USERNAME=postgres"
echo "     DB_PASSWORD=sua-senha-supabase"
echo ""
echo "  2. Configure as variáveis do Supabase no frontend/.env:"
echo "     VITE_API_URL=http://localhost:8000/api"
echo ""
echo "  3. Conecte ao GitHub:"
echo "     git remote add origin https://github.com/SEU-USUARIO/gestao-chamados.git"
echo "     git push -u origin main"
echo ""
echo "  4. Execute as migrations:"
echo "     cd backend && php artisan migrate --seed"
echo ""
echo "  5. Inicie os servidores:"
echo "     Terminal 1: cd backend && php artisan serve"
echo "     Terminal 2: cd frontend && npm run dev"
