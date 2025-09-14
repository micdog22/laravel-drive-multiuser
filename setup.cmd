@echo off
setlocal enabledelayedexpansion
set APP_NAME=%1
if "%APP_NAME%"=="" set APP_NAME=laravel-drive

echo [1/7] Criando app Laravel: %APP_NAME%
composer create-project laravel/laravel "%APP_NAME%"
cd "%APP_NAME%"

echo [2/7] Requerendo dependencias
composer require laravel/breeze google/apiclient

echo [3/7] Instalando Breeze (Blade)
php artisan breeze:install blade --dark

echo [4/7] Copiando modulos do kit
for /f "delims=" %%i in ('cd') do set HERE=%%i
xcopy "%HERE%\..\modules" "%cd%\" /E /I /Y

echo [5/7] npm install/build
npm install
npm run build

echo [6/7] Migracoes + seed
php artisan migrate
php artisan db:seed --class=AdminUserSeeder

echo [7/7] Pronto. Ajuste .env e coloque storage\app\google\credentials.json
