# STAGE 1: Build PHP Dependencies
FROM php:8.4-fpm-alpine AS builder

WORKDIR /app

# Install system dependencies & Composer
RUN apk add --no-cache curl git unzip libpng-dev libjpeg-turbo-dev freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy project files
COPY . .

# Run composer install untuk production
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ==========================================
# STAGE 2: Final Production Image (Poin 10)
# ==========================================
FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

# Salin ekstensi yang diperlukan saja dari alpine base
RUN docker-php-ext-install pdo_mysql

# Salin HANYA hasil akhir build dari STAGE 1 (Poin 16)
COPY --from=builder /app /var/www/html

# Set ownership dan permission yang aman untuk Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]