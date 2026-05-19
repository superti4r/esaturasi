FROM php:8.4-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl git unzip ca-certificates netcat-openbsd \
    nginx build-essential \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libzip-dev \
    libcurl4-openssl-dev libssl-dev libxml2-dev zlib1g-dev libicu-dev && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-configure intl && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath zip gd curl xml intl

RUN curl -sL https://deb.nodesource.com/setup_22.x | bash - && \
    apt-get install -y --no-install-recommends nodejs && \
    rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json ./
RUN composer install --no-scripts --optimize-autoloader --no-interaction --no-ansi --ignore-platform-req=ext-xml || \
    composer install --no-scripts --no-cache --optimize-autoloader --no-interaction --no-ansi --ignore-platform-req=ext-xml

COPY package.json ./
RUN npm install || npm ci && (npm run build || true)

COPY . .

RUN mkdir -p storage bootstrap/cache && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

COPY docker/nginx/main.conf /etc/nginx/sites-available/default
RUN rm -f /etc/nginx/sites-enabled/default && \
    ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80 443

HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD [""]