# Base image PHP dengan Nginx & Supervisor
# Menggunakan Alpine Linux agar ukuran image kecil
FROM php:8.2-fpm-alpine as base

# Set direktori kerja di dalam container
WORKDIR /var/www/html

# Install dependensi sistem (termasuk Node.js & npm) dan ekstensi PHP
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip bcmath

# Hapus cache untuk menjaga ukuran image tetap kecil
RUN rm -rf /var/cache/apk/*

# Salin file konfigurasi Nginx dan Supervisor dari lokal ke container
COPY .docker/nginx.conf /etc/nginx/http.d/default.conf
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Salin semua file aplikasi dari lokal ke container
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependensi PHP tanpa dev packages & optimasi autoloader
RUN composer install --optimize-autoloader --no-dev

# Install dependensi Node.js dan build asset frontend
RUN npm install
RUN npm run build

# Atur kepemilikan file agar server bisa menulis ke folder storage & bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Buka port 80 (internal container) dan jalankan aplikasi
EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]