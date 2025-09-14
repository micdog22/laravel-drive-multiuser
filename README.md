# Laravel Drive Multiuser — Kit de Bootstrap

Este kit instala e configura automaticamente um projeto **Laravel 11** com:
- Autenticação (Laravel Breeze).
- Papéis (admin/usuário) e *impersonate*.
- Upload para **Google Drive** por usuário (pasta por usuário).
- Cotas por usuário com bloqueio de upload ao exceder.
- Painel admin: gerenciar usuários, status (ativo/inativo), cotas e visualizar área de qualquer usuário.

> Por que é um **kit**? Para manter o ZIP pequeno e confiável. O script cria o projeto Laravel oficial via Composer e aplica os módulos prontos.

## Requisitos
- PHP 8.2+
- Composer
- Node.js + npm (para Breeze/Vite)
- MySQL/MariaDB
- Credenciais OAuth do Google (Drive API habilitada)

## Instalação Rápida
```bash
# 1) Executar o instalador
bash setup.sh my-app

cd my-app

# 2) Configurar .env
cp .env.example .env
php artisan key:generate

# 3) Configurar banco no .env e rodar migrações/seed
php artisan migrate --seed

# 4) Instalar front do Breeze
npm install
npm run build   # ou: npm run dev

# 5) Colocar o credentials.json do Google
mkdir -p storage/app/google
cp /caminho/para/credentials.json storage/app/google/credentials.json

# 6) Subir servidor
php artisan serve
```

Após logar, acesse **/admin** (criado um usuário admin padrão no seeder).

## O que o instalador faz
- `composer create-project laravel/laravel`
- `composer require laravel/breeze google/apiclient`
- `php artisan breeze:install blade`
- Copia e aplica os **módulos** deste kit (models, controllers, migrations, policies, views e rotas).
- Publica assets e pronto.

## Google OAuth
- Callback: `APP_URL/google/callback`
- Tokens por usuário: `storage/app/google/tokens/{user_id}.json`
- Escopo: `https://www.googleapis.com/auth/drive.file`

## Usuário admin inicial
- E-mail: `admin@example.com`
- Senha: `password`
- Altere imediatamente após o primeiro acesso.

## Licença
MIT.
