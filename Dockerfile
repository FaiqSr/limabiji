# ─────────────────────────────────────────────────────────────────────────────
# Stage 1: Node builder — compile front-end assets (Vite + Tailwind CSS v4)
# ─────────────────────────────────────────────────────────────────────────────
FROM node:22-alpine AS node-builder

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts

COPY . .
RUN npm run build

# ─────────────────────────────────────────────────────────────────────────────
# Stage 2: Composer — install PHP dependencies (no dev)
# ─────────────────────────────────────────────────────────────────────────────
FROM composer:2 AS composer-builder

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-interaction \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .
RUN composer run-script post-autoload-dump --no-interaction 2>/dev/null || true

# ─────────────────────────────────────────────────────────────────────────────
# Stage 3: Production — PHP-FPM runtime
# ─────────────────────────────────────────────────────────────────────────────
FROM php:8.4-fpm-alpine AS production

LABEL maintainer="Lima Biji <hello@limabiji.com>"
LABEL description="Lima Biji specialty coffee — Laravel 13 application"

# System dependencies
RUN apk add --no-cache \
    # Image processing
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    # Multibyte strings
    oniguruma-dev \
    # Intl / ICU
    icu-dev \
    icu-libs \
    # SQLite
    sqlite-dev \
    sqlite \
    # Misc tools
    zip \
    unzip \
    curl \
    nginx \
    supervisor \
    # Required by opcache
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_sqlite \
        gd \
        intl \
        bcmath \
        mbstring \
        opcache \
        pcntl

# PHP config tuning
COPY docker/php/php.ini /usr/local/etc/php/conf.d/lima-biji.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Nginx config
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Supervisor (manages php-fpm + nginx + queue worker in one container)
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

WORKDIR /var/www/html

# Copy compiled vendor from composer-builder
COPY --from=composer-builder /app/vendor ./vendor

# Copy compiled public/build from node-builder
COPY --from=node-builder /app/public/build ./public/build

# Copy application source
COPY . .

# Remove files that must not ship inside the image
RUN rm -rf \
    node_modules \
    .git \
    .env \
    .env.* \
    tests \
    docker

# Ensure SQLite database file exists and storage/bootstrap/cache are writable
RUN touch database/database.sqlite \
    && chown -R www-data:www-data \
        database \
        storage \
        bootstrap/cache \
    && chmod -R 775 \
        database \
        storage \
        bootstrap/cache

EXPOSE 80

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
