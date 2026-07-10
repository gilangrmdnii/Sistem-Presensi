#!/usr/bin/env bash
# Skrip start container di Render: set port, migrasi DB, warm-up cache, jalankan server.
set -e

# Render meng-inject $PORT (default 8080 bila kosong / lokal)
: "${PORT:=8080}"
sed -i "s/__PORT__/${PORT}/g" /etc/nginx/nginx.conf

echo "==> Migrasi database"
php artisan migrate --force || true

echo "==> Symlink storage"
php artisan storage:link || true

echo "==> Rebuild cache (pakai env live)"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Seeder opsional: set RUN_SEED=true di Environment Render untuk mengisi data,
# lalu hapus/false lagi setelah data masuk agar tidak diulang tiap deploy.
if [ "${RUN_SEED}" = "true" ]; then
    echo "==> RUN_SEED=true -> seeding data"
    php artisan db:seed --force
fi

echo "==> Menjalankan php-fpm + nginx di port ${PORT}"
php-fpm -D
nginx -g 'daemon off;'
