#!/usr/bin/env bash
set -euo pipefail
APP_NAME="${1:-laravel-drive}"
echo "[1/7] Criando app Laravel: $APP_NAME"
composer create-project laravel/laravel "$APP_NAME"

cd "$APP_NAME"
echo "[2/7] Requerendo dependências"
composer require laravel/breeze google/apiclient

echo "[3/7] Instalando Breeze (Blade)"
php artisan breeze:install blade --dark

echo "[4/7] Copiando módulos do kit"
KIT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
rsync -a "$KIT_DIR/modules/" "./"

echo "[5/7] Rodando npm install/build (pode demorar)"
npm install
npm run build || true

echo "[6/7] Aplicando migrações e seed"
php artisan migrate
php artisan db:seed --class=AdminUserSeeder

echo "[7/7] Concluído! Ajuste o .env, gere key e coloque o credentials.json em storage/app/google."
echo "cd $APP_NAME && php artisan serve"
