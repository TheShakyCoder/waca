# ---------------------------------------------------------------------------
# Multi-stage build — Laravel + Inertia SSR (PHP-FPM + nginx + Node.js)
#
# Base:     nikolaik/php-nodejs:8.4-fpm-noble  (PHP 8.4 FPM + Node 20 + nginx)
# Stages:   deps → builder → production
# ---------------------------------------------------------------------------

# ---- 1. deps — install PHP extensions & Composer ----------------------------
FROM nikolaik/php-nodejs:8.4-fpm-noble AS deps

ARG IMAGE_VERSION=latest
LABEL org.opencontainers.image.version="${IMAGE_VERSION}"

RUN apt-get update && apt-get install -y --no-install-recommends \
        git curl wget unzip \
        libpng-dev libonig-dev libxml2-dev libzip-dev \
        libfreetype-dev libjpeg62-turbo-dev libwebp-dev libavif-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        gd mbstring exif pcntl bcmath opcache zip sodium \
    && apt-get remove -y git curl wget unzip \
    && apt-get autoremove -y && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer self-update 2.8.4

WORKDIR /app

# ---- 2. builder — install deps, compile assets ------------------------------
FROM deps AS builder

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY package.json package-lock*.json ./
RUN npm ci && npm cache clean --force

COPY . .

RUN npm run build

# ---- 3. production — slim runtime image ------------------------------------
FROM deps AS production

ARG IMAGE_VERSION=latest
LABEL org.opencontainers.image.version="${IMAGE_VERSION}"

WORKDIR /app

# Built application from builder
COPY --from=builder /app .

# Self-contained nginx config (bypasses Nixpacks template engine)
COPY nixpacks-nginx.conf /etc/nginx.conf

# Startup script
COPY docker-start.sh /app/docker-start.sh
RUN chmod +x /app/docker-start.sh

# Ensure www-data can write runtime dirs; nginx temp dirs ready
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && mkdir -p /tmp/nginx-client-body /tmp/nginx-proxy /tmp/nginx-fastcgi /tmp/nginx-uwsgi /tmp/nginx-scgi

EXPOSE 80

ENTRYPOINT ["/app/docker-start.sh"]
