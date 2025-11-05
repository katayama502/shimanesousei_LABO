#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR="bu-ousen"

if [ -d "$PROJECT_DIR" ]; then
  echo "[setup] Project directory '$PROJECT_DIR' already exists. Skipping scaffolding." >&2
  exit 0
fi

composer create-project laravel/laravel "$PROJECT_DIR"
cd "$PROJECT_DIR"
php artisan key:generate
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run build
composer require jeroennoten/laravel-adminlte
php artisan adminlte:install

cd ..
rsync -a scaffold/ "$PROJECT_DIR"/

cat <<'ENV' > "$PROJECT_DIR"/.env.example
APP_NAME="Minna-no-Bukatsu"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bu_ousen
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1
ENV

cp .env.example .env

php artisan migrate --seed
npm run build
