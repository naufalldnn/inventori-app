FROM php:8.3-cli

WORKDIR /var/www/html

ARG INSTALL_DEV=true

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libpq-dev libsqlite3-dev libzip-dev libonig-dev libxml2-dev nodejs npm \
    && docker-php-ext-install pdo_pgsql pgsql pdo_sqlite zip mbstring pcntl dom \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json ./
RUN if [ "$INSTALL_DEV" = "true" ]; then \
        composer install --prefer-dist --no-interaction --no-scripts --no-progress; \
    else \
        composer install --no-dev --prefer-dist --no-interaction --no-scripts --no-progress; \
    fi

COPY package.json ./
RUN npm install

COPY . .
COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

RUN composer dump-autoload --optimize \
    && php artisan package:discover --ansi \
    && npm run build \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

ENTRYPOINT ["entrypoint"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
