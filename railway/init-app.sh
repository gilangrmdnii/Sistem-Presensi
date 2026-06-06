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

# Seeder opsional: aktifkan dengan menambah env var RUN_SEED=true di Railway.
# Setelah data masuk, hapus/ubah ke false biar absensi tidak di-acak ulang tiap deploy.
if [ "$RUN_SEED" = "true" ]; then
    echo "==> RUN_SEED=true -> seeding karyawan + absensi 15 hari"
    php artisan db:seed --class=RealEmployeeSeeder --force
fi

echo "==> Init done"
