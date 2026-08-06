#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

# Ensure Laravel's writable directories exist (Coolify volumes may mount empty).
mkdir -p \
    storage/app/public \
    storage/app/private \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/testing \
    storage/logs \
    bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Storage symlink (public/storage -> storage/app/public). Safe to run repeatedly.
if [ ! -L public/storage ]; then
    php artisan storage:link --quiet || true
fi

# Wait for the database before running migrations. DB_HOST/DB_PORT come from Coolify env.
if [ "${DB_CONNECTION:-mysql}" != "sqlite" ] && [ -n "${DB_HOST:-}" ]; then
    echo "Waiting for database at ${DB_HOST}:${DB_PORT:-3306}..."
    for i in $(seq 1 30); do
        if php -r "exit(@fsockopen(getenv('DB_HOST'), (int)(getenv('DB_PORT') ?: 3306)) ? 0 : 1);"; then
            echo "Database is reachable."
            break
        fi
        if [ "$i" -eq 30 ]; then
            echo "Database did not become reachable in time; continuing anyway." >&2
        fi
        sleep 2
    done
fi

# Cache config/routes/views with the *runtime* env (Coolify injects vars at start).
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run pending migrations on boot. Disable RUN_MIGRATIONS=false to opt out.
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force
fi

exec "$@"
