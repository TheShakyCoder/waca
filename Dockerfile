# syntax=docker/dockerfile:1

# ── Stage 1: Frontend build ────────────────────────────────────────────────
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
# Keep devDependencies available because Vite and related build tools normally
# live there. Avoid NODE_ENV=production until after the assets are built.
RUN npm ci

COPY . .
RUN npm run build

# ── Stage 2: Shared PHP runtime ────────────────────────────────────────────
FROM php:8.4-fpm-alpine AS php-base

LABEL maintainer="Sharif Khan"

ENV APP_ENV=production \
    APP_DEBUG=false \
    APP_NAME="waca"

# This helper installs the correct Alpine build/runtime dependencies for each
# extension and removes temporary compiler packages afterwards.
COPY --from=ghcr.io/mlocati/php-extension-installer:2.11.12 \
    /usr/bin/install-php-extensions \
    /usr/local/bin/install-php-extensions

RUN apk add --no-cache \
      curl \
      imagemagick \
      nginx \
      supervisor \
      tini \
      unzip \
      zip \
  && install-php-extensions \
      bcmath \
      gd \
      gmp \
      imagick \
      pcntl \
      pdo_mysql \
      redis \
      zip

WORKDIR /app

# ── Stage 3: Composer dependencies ────────────────────────────────────────
FROM php-base AS vendor

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./

# Laravel's Composer scripts call artisan, which is not present yet at this
# point. Install packages first without scripts so this layer remains cacheable.
RUN composer install \
      --no-dev \
      --no-interaction \
      --no-progress \
      --no-scripts \
      --optimize-autoloader \
      --prefer-dist

COPY . .

# Rebuild the optimized autoloader now that application classes exist.
RUN composer dump-autoload \
      --no-dev \
      --no-interaction \
      --no-scripts \
      --optimize

# ── Stage 4: Production image ──────────────────────────────────────────────
FROM php-base AS production

WORKDIR /app

COPY . .
COPY --from=vendor /app/vendor ./vendor

# Vite output generated in the frontend stage.
COPY --from=frontend /app/public/build ./public/build

# Build Laravel's package manifest only after artisan and vendor are available.
# Configuration caching is deliberately left for runtime/deployment so Coolify's
# runtime environment values are not frozen into the image.
RUN php artisan package:discover --ansi \
  && mkdir -p \
      bootstrap/cache \
      storage/framework/cache/data \
      storage/framework/sessions \
      storage/framework/views \
      storage/logs \
      /var/log/supervisor \
      /run/php-fpm \
      /var/run \
  && chown -R www-data:www-data bootstrap/cache storage \
  && chmod -R 775 bootstrap/cache storage

# ── Runtime configuration ─────────────────────────────────────────────────
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/supervisord.conf /etc/supervisor/supervisord.conf

EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=5s --start-period=15s --retries=3 \
  CMD wget -qO- http://localhost:8080/robots.txt >/dev/null || exit 1

ENTRYPOINT ["tini", "--"]
CMD ["supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
