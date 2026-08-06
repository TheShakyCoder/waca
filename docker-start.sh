#!/usr/bin/env bash
#
# Container entrypoint — Laravel boot steps then nginx / php-fpm.
#
# Runs DB-dependent and env-dependent tasks at container boot (where the
# database service is reachable), then starts php-fpm and nginx using the
# self-contained config shipped in this repo (nixpacks-nginx.conf → /etc/nginx.conf).
#

set -euo pipefail

echo "==> [startup] artisan migrate --force"
php artisan migrate --force --no-interaction

echo "==> [startup] artisan storage:link (idempotent)"
php artisan storage:link 2>/dev/null || true

echo "==> [startup] artisan config/route/view/event cache"
# Cache at runtime so the compiled artefacts reflect Coolify's env vars,
# not the (possibly empty) env that existed during the Docker build.
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "==> [startup] preparing nginx temp dirs"
mkdir -p /tmp/nginx-client-body /tmp/nginx-proxy /tmp/nginx-fastcgi /tmp/nginx-uwsgi /tmp/nginx-scgi

echo "==> [startup] starting php-fpm (background)"
# nikolaik/php-nodejs ships PHP-FPM configured on 127.0.0.1:9000 —
# exactly what nixpacks-nginx.conf's fastcgi_pass expects.
php-fpm --daemonize

echo "==> [startup] exec nginx"
# /etc/nginx.conf has `daemon off;` built in, so no -g flag needed.
exec nginx -c /etc/nginx.conf
