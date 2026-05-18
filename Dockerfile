FROM php:8.4-fpm-alpine AS base

WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    curl \
    freetype-dev \
    icu-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    mysql-client \
    oniguruma-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    bcmath \
    exif \
    gd \
    intl \
    opcache \
    pcntl \
    pdo_mysql \
    zip

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

FROM base AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .

RUN composer dump-autoload --optimize

FROM node:22-alpine AS assets

WORKDIR /app

COPY package*.json ./

RUN if [ -f package.json ]; then \
    if [ -f package-lock.json ]; then npm ci; else npm install; fi; \
    fi

COPY . .

RUN if [ -f package.json ]; then npm run build; else mkdir -p public/build; fi

FROM base AS production

ENV APP_ENV=production

COPY --from=vendor --chown=www-data:www-data /var/www/html /var/www/html
COPY --from=assets --chown=www-data:www-data /app/public/build /var/www/html/public/build

COPY docker/php/entrypoint.sh /usr/local/bin/app-entrypoint

RUN chmod +x /usr/local/bin/app-entrypoint \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && cp -a public public-dist \
    && chown -R www-data:www-data storage bootstrap/cache public public-dist

ENTRYPOINT ["app-entrypoint"]

CMD ["php-fpm", "-F"]