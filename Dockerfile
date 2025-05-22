FROM php:8.2-cli-alpine AS build

RUN apk add --no-cache \
    bash \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    libpq \
    curl-dev \
    openssl-dev \
    gcc \
    g++ \
    make \
    autoconf \
    pkgconf \
 && docker-php-ext-install \
    ftp \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
 && rm -rf /var/cache/apk/*

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

COPY . .

FROM php:8.2-cli-alpine

RUN apk add --no-cache \
    bash \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    libpq \
    curl-dev \
    openssl-dev \
 && docker-php-ext-install \
    ftp \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
 && rm -rf /var/cache/apk/*

WORKDIR /app

COPY --from=build /app /app

RUN chmod -R 755 storage bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
