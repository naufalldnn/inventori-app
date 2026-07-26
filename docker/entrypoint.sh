#!/bin/sh
set -e

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
fi

if [ -f .env ] && ! grep -q '^APP_KEY=base64:' .env && [ -z "${APP_KEY:-}" ]; then
    php artisan key:generate --force --no-interaction >/dev/null 2>&1 || true
fi

if [ ! -f public/build/manifest.json ]; then
    npm run build
fi

exec "$@"
