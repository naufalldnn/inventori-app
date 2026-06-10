FROM php:8.3-cli

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libpq-dev libzip-dev libonig-dev nodejs npm \
    && docker-php-ext-install pdo_pgsql pgsql zip mbstring pcntl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-scripts --no-progress

COPY package.json ./
RUN npm install

COPY . .

RUN composer dump-autoload --optimize \
    && php artisan package:discover --ansi \
    && npm run build \
    && mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["sh", "-c", "test -f .env || cp .env.example .env; php artisan key:generate --force --no-interaction >/dev/null 2>&1 || true; npm run build >/dev/null 2>&1 || true; php artisan serve --host=0.0.0.0 --port=8000"]
