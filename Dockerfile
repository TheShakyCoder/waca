# syntax=docker/dockerfile:1.7

# ---------- Stage 1: Install PHP dependencies ----------
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

# Install without running scripts (artisan isn't available yet) and without dev deps.
RUN composer install \
      --no-dev \
      --no-interaction \
      --no-progress \
      --prefer-dist \
      --no-scripts \
      --optimize-autoloader

# Bring in the rest of the app so the autoloader can be regenerated against real source.
COPY . .

RUN composer dump-autoload --no-dev --optimize --classmap-authoritative


# ---------- Stage 2: Build frontend assets with Vite ----------
FROM node:20-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY resources ./resources
COPY public ./public
COPY vite.config.js jsconfig.json ./

# Ziggy ships its JS helpers inside the composer package; Vite needs them to resolve
# `../../vendor/tightenco/ziggy` from resources/js/app.js.
COPY --from=vendor /app/vendor/tightenco/ziggy ./vendor/tightenco/ziggy

RUN npm run build


# ---------- Stage 3: Runtime image (nginx + php-fpm) ----------
FROM php:8.4-fpm-alpine AS runtime

# System packages: nginx for the web layer, supervisord to run nginx + php-fpm together,
# plus libraries needed by the PHP extensions we install below.
RUN apk add --no-cache \
        nginx \
        supervisor \
        bash \
        tini \
        icu-libs \
        libzip \
        libpng \
        oniguruma \
    && apk add --no-cache --virtual .build-deps \
        autoconf \
        g++ \
        make \
        icu-dev \
        libzip-dev \
        libpng-dev \
        oniguruma-dev \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        bcmath \
        intl \
        zip \
        gd \
        opcache \
        pcntl \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/*

# Production-tuned PHP + opcache settings.
COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/zz-opcache.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/zz-www.conf

# Nginx + supervisord configs.
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord/supervisord.conf /etc/supervisord.conf

# Entrypoint runs migrations and caches config/routes/views on container start.
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html

# Copy the prepared application from the previous stages.
COPY --from=vendor /app /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build

# Laravel needs storage/ and bootstrap/cache writable by the php-fpm/nginx user.
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html/storage -type d -exec chmod 775 {} \; \
    && find /var/www/html/bootstrap/cache -type d -exec chmod 775 {} \;

# nginx runs its workers as www-data (see docker/nginx/nginx.conf), so it must own
# the temp dirs where large FastCGI responses (e.g. generated PDFs) are buffered to
# disk. Without this, big responses fail with "open() … failed (13: Permission denied)"
# and get truncated mid-stream.
RUN mkdir -p /var/lib/nginx/tmp/fastcgi \
    && chown -R www-data:www-data /var/lib/nginx

EXPOSE 80

ENTRYPOINT ["/sbin/tini", "--", "/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
