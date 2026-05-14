#!/bin/sh
set -e

# Wait for database to be ready
echo "Waiting for database..."
for i in {1..30}; do
    if PGPASSWORD=$DATABASE_PASSWORD pg_isready -h db -U sensor_user -d sensor_db > /dev/null 2>&1; then
        echo "Database is ready!"
        break
    fi
    echo "Database unavailable - waiting... ($i/30)"
    sleep 1
done

# Create database if it doesn't exist
PGPASSWORD=$DATABASE_PASSWORD psql -h db -U sensor_user -d postgres -tc "SELECT 1 FROM pg_database WHERE datname = 'sensor_db'" | grep -q 1 || \
PGPASSWORD=$DATABASE_PASSWORD createdb -h db -U sensor_user sensor_db || true

# Run migrations
php bin/console doctrine:database:create --if-not-exists 2>/dev/null || true
php bin/console doctrine:migrations:migrate --no-interaction

# Compile assets
php bin/console asset-map:compile

# Ensure public directory is writable
chmod -R 777 public/

echo "Database setup complete!"

# Start PHP-FPM
php-fpm
