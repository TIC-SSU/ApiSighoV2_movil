# Imagen base PHP con FPM (puede ser cli si prefieres)
FROM php:8.2-cli

# Instala dependencias del sistema necesarias incluyendo libpq-dev para pdo_pgsql y nano
RUN apt-get update && apt-get install -y \
    zip unzip curl git libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev nano \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip ftp pdo_pgsql

# Copia Composer desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /app

# Copia el código del proyecto al contenedor
COPY . .

# Instala las dependencias de Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Asigna permisos correctos (ajustado al WORKDIR actual)
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage

# Comando por defecto al iniciar el contenedor
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
