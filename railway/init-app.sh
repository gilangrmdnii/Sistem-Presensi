#!/bin/bash
# Pre-deploy script untuk Railway.
# PENTING urutannya: bersihkan dulu cache config yang mungkin dibuat saat BUILD
# (nilai env saat build belum lengkap, mis. referensi ${{MySQL.*}} belum resolve),
# supaya migrate & seed memakai environment variable LIVE saat deploy.
set -e

echo "==> Clearing build-time caches (use live env)"
php artisan optimize:clear

echo "==> Running database migrations"
php artisan migrate --force

echo "==> Linking storage (public disk)"
php artisan storage:link || true

# Seeder opsional: aktifkan dengan menambah env var RUN_SEED=true di Railway.
# Setelah data masuk, hapus/ubah ke false biar absensi tidak di-acak ulang tiap deploy.
if [ "$RUN_SEED" = "true" ]; then
    echo "==> RUN_SEED=true -> seeding karyawan + absensi 15 hari"
    php artisan db:seed --class=RealEmployeeSeeder --force
fi

echo "==> Rebuilding caches with live env"
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

echo "==> Init done"
