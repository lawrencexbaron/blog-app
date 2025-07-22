FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    curl \
    git \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libmcrypt-dev \
    libicu-dev \
    libxslt-dev \
    default-mysql-client \
    supervisor

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath intl

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 8000
EXPOSE 9000