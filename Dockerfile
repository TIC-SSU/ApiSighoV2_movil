# Etapa 1: Builder
FROM php:8.2-cli AS build

# Instala las extensiones necesarias, incluyendo ftp
RUN apt-get update && apt-get install -y \
    zip unzip curl git libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev ftp \
    && docker-php-ext-install ftp pdo_mysql mbstring exif pcntl bcmath gd zip pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala Composer manualmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copia solo los archivos necesarios para instalar dependencias
COPY composer.json composer.lock ./

# Instala dependencias sin dev ni scripts
RUN composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader

# Copia el resto del código
COPY . .

# Etapa 2: Imagen final
FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev ftp \
    && docker-php-ext-install ftp pdo_mysql mbstring exif pcntl bcmath gd zip pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY --from=build /app /app

RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
