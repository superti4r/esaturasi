# syntax=docker/dockerfile:1.7

# --- Node build stage (Vite assets) ---
FROM node:20-alpine AS node-build
WORKDIR /app

COPY package.json package-lock.json* pnpm-lock.yaml* yarn.lock* ./
RUN \
  if [ -f package-lock.json ]; then npm ci; \
  elif [ -f pnpm-lock.yaml ]; then corepack enable && pnpm i --frozen-lockfile; \
  elif [ -f yarn.lock ]; then yarn install --frozen-lockfile; \
  else npm i; fi

COPY resources ./resources
COPY vite.config.js ./
COPY public ./public

# Some Laravel Vite setups read version from composer.json; safe to include
COPY composer.json ./

RUN npm run build


# --- PHP build stage (vendor) ---
FROM composer:2 AS php-vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
  --no-dev \
  --prefer-dist \
  --no-interaction \
  --no-progress \
  --optimize-autoloader


# --- Runtime stage (php-fpm) ---
FROM php:8.4-fpm-alpine

# System deps
RUN apk add --no-cache \
    bash \
    curl \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    zip \
    unzip \
    mysql-client \
    su-exec \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) pdo_mysql mbstring zip intl gd opcache \
  && rm -rf /var/cache/apk/*

# Install Redis extension
RUN apk add --no-cache $PHPIZE_DEPS \
  && pecl install redis \
  && docker-php-ext-enable redis \
  && apk del --no-network $PHPIZE_DEPS

# PHP settings
COPY docker/php/conf.d /usr/local/etc/php/conf.d

WORKDIR /var/www/html

# App sources
COPY --chown=www-data:www-data . .

# Bring in vendor + built assets
COPY --from=php-vendor /app/vendor ./vendor
COPY --from=node-build /app/public/build ./public/build

# Laravel writable dirs
RUN mkdir -p storage bootstrap/cache \
  && chown -R www-data:www-data storage bootstrap/cache

COPY docker/php/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm", "-F"]
