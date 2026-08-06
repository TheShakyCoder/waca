# ── Stage 1: Frontend build (Node.js 20) ───────────────────────────────────
FROM node:20-alpine AS frontend

ARG NODE_ENV=production
ENV NODE_ENV=${NODE_ENV}

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts

COPY . .
RUN npm run build

# ── Stage 2: Production — PHP-FPM + nginx (PHP 8.4) ───────────────────────
# NOTE: php:*-fpm-alpine already ships www-data, GD, bcmath, pdo_mysql, etc.
FROM php:8.4-fpm-alpine

LABEL maintainer="Sharif Khan"

ARG USER_ID=1000
ARG GROUP_ID=1000

ENV APP_ENV=production \
    APP_DEBUG=false \
    APP_NAME="waca"

# ── System packages & PECL extensions ───────────────────────────────────────
# PHP-FPM Alpine image has GD, bcmath, pdo_mysql pre-compiled — no dev headers needed.
RUN apk add --no-cache \
      imagemagick imagemagick-dev \
      nginx supervisor tini zip unzip curl \
  && pecl install redis gmp imagick \
  && docker-php-ext-install pdo_mysql mbstring tokenizer xml pcntl bcmath gd ctype json fileinfo iconv sodium opcache \
  && docker-php-ext-enable redis gmp imagick opcache \
  && rm -rf /var/cache/apk/*

# ── Composer (multi-stage, only needed at build time) ──────────────────────
WORKDIR /app
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader && rm -rf ~/.composer

# ── Application source ──────────────────────────────────────────────────────
COPY . .
COPY --from=frontend /app/public/build ./public/build   # Vite output — not local

# Persist Laravel caches so artisan doesn't re-warm each request.
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# ── Runtime configs ─────────────────────────────────────────────────────────
COPY docker/nginx.conf       /etc/nginx/nginx.conf
COPY docker/www.conf         /usr/local/etc/php-fpm.d/www.conf
RUN mkdir -p /var/log/supervisor /run/php-fpm /var/run
COPY docker/supervisord.conf /etc/supervisor/supervisord.conf

# ── Permissions (www-data already exists in the PHP Alpine base) ────────────
RUN chown -R www-data:www-data app bootstrap/cache storage \
  && chmod -R 775      storage bootstrap/cache

EXPOSE 8080

# Check nginx is serving — robots.txt is a real file, no PHP/DB needed.
HEALTHCHECK --interval=30s --timeout=5s --start-period=15s --retries=3 \
  CMD wget -qO- http://localhost:8080/robots.txt >/dev/null || exit 1

# tini handles PID 1 signal forwarding; supervisord runs both processes.
ENTRYPOINT ["tini", "--"]
CMD ["supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
