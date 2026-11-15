#!/bin/bash
set -e

# Render / Railway dynamically assigns $PORT (fallback to 80 if not set)
PORT="${PORT:-80}"
echo "Configuring Apache to listen on port ${PORT}..."
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Ensure storage subdirectories exist
mkdir -p /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/logs

# Clear any precompiled views or caches from build time
echo "Clearing previous caches..."
php artisan optimize:clear || true

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force || true

# Optionally seed database if SEED_DATABASE=true
if [ "${SEED_DATABASE}" = "true" ]; then
    echo "SEED_DATABASE is true, seeding database..."
    php artisan db:seed --force || true
fi

# Cache configurations, routes, and views for production
echo "Caching Laravel configuration and routes..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Fix permissions so Apache (www-data) can read & write storage and cache
echo "Setting file permissions for Apache..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Starting Apache web server on port ${PORT}..."
exec apache2-foreground
