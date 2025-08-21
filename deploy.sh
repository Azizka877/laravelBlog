#!/bin/sh
set -e

echo "Starting Laravel application..."

# Optimiser l'application pour la production
php artisan optimize
php artisan view:cache
php artisan route:cache
php artisan config:cache

echo "Application started successfully!"