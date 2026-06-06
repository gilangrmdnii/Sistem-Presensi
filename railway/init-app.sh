#!/bin/bash
# Pre-deploy script untuk Railway.
# Dijalankan SEBELUM versi baru aktif: migrasi DB + warm-up cache config/route/view.
set -e

echo "==> Running database migrations"
php artisan migrate --force

echo "==> Linking storage (public disk)"
php artisan storage:link || true

echo "==> Rebuilding caches"
php artisan optimize:clear
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

echo "==> Init done"
