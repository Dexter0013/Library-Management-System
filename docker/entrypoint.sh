#!/bin/bash
set -e

# Railway dynamically assigns $PORT (fallback to 80 if not set)
PORT="${PORT:-80}"
echo "Configuring Apache to listen on port ${PORT}..."
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Cache configurations, routes, and views for production
echo "Optimizing Laravel cache..."
php artisan config:clear
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force || true

# Optionally seed database if SEED_DATABASE=true in environment
if [ "${SEED_DATABASE}" = "true" ]; then
    echo "SEED_DATABASE is true, seeding database..."
    php artisan db:seed --force || true
fi

echo "Starting Apache web server on port ${PORT}..."
exec apache2-foreground
