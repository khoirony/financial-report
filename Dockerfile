# Menggunakan Alpine Linux agar ukuran image kecil
FROM php:8.2-fpm-alpine

# Set direktori kerja
WORKDIR /var/www/html

# 1. Install System Dependencies & PHP Extensions
# Kita install 'chromium' dan library pendukungnya di sini agar Browsershot bisa jalan
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    zip \
    unzip \
    nodejs \
    npm \
    # Library Gambar & Zip
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    icu-libs \
    # --- TAMBAHAN WAJIB UNTUK BROWSERSHOT DI ALPINE ---
    chromium \
    nss \
    freetype \
    harfbuzz \
    ca-certificates \
    ttf-freefont \
    # --------------------------------------------------
    # Install build dependencies (untuk compile PHP ext)
    && apk add --no-cache --virtual .build-deps \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    # Install Ekstensi PHP (termasuk exif, gd, bcmath)
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip bcmath exif opcache intl \
    # Hapus build dependencies agar image kecil
    && apk del .build-deps \
    && rm -rf /var/cache/apk/*

# 2. Setup Konfigurasi Server
COPY .docker/nginx.conf /etc/nginx/http.d/default.conf
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Install PHP Dependencies (Copy composer file dulu agar cache jalan)
COPY composer.json composer.lock ./
# --ignore-platform-reqs opsional, jaga-jaga jika ada perbedaan minor environment
RUN composer install --optimize-autoloader --no-dev --no-scripts

# 5. Copy sisa source code aplikasi
COPY . .

# 6. Install Node Dependencies & Build Assets
RUN npm install && npm run build && rm -rf node_modules

# 7. Finalisasi Permission & Autoload
RUN composer dump-autoload --optimize
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Jalankan
EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]