# ---- Sistem Presensi MAZ — image untuk Render.com ----
FROM php:8.2-fpm

# Dependensi sistem + ekstensi PHP (gd, bcmath, zip, intl, pdo_mysql, opcache)
RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx git unzip ca-certificates \
        libicu-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql bcmath gd zip intl opcache \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node.js (untuk build asset Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY . .

# Install dependency PHP (tanpa dev) + build asset front-end.
# Pakai `npm install` (bukan `npm ci`) karena package-lock.json tidak di-commit.
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm install --no-audit --no-fund \
    && npm run build \
    && rm -rf node_modules \
    && chown -R www-data:www-data storage bootstrap/cache

# Konfigurasi nginx + skrip start
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Render meng-inject $PORT; nginx listen ke port itu (default 8080 saat lokal)
EXPOSE 8080
CMD ["start.sh"]
