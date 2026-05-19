FROM --platform=${BUILDPLATFORM} php:8.4-fpm as builder

ARG BUILDPLATFORM
ARG TARGETPLATFORM

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl git unzip ca-certificates \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libzip-dev \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json ./

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY package.json ./

RUN npm install && npm run build

FROM --platform=${TARGETPLATFORM} php:8.4-fpm

ARG TARGETPLATFORM

RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx curl ca-certificates \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN useradd -m -s /bin/bash -u 1000 appuser || true

WORKDIR /var/www/html

COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html

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