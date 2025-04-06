#!/bin/bash
set -e

echo "🏁 Running Symfony DB migrations..."

# Install PHP dependencies (optional but useful in dev)
if [ ! -d "vendor" ]; then
  echo "📦 Installing composer dependencies..."
  composer install --prefer-dist --no-interaction
fi

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL..."
until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
  echo "⏳ Still waiting for MySQL..."
  sleep 2
done

# Run Doctrine migrations
echo "📂 Applying migrations..."
php bin/console doctrine:migrations:migrate --no-interaction || true

# Hand off to FrankenPHP
echo "🚀 Starting FrankenPHP..."
exec frankenphp run --config /etc/caddy/Caddyfile "$@"