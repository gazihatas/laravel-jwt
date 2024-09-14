FROM php:8.3-fpm
LABEL authors="gazihatas"

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    supervisor \
    libevent-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libpq-dev \
    iputils-ping

RUN docker-php-ext-install pdo_pgsql zip exif pcntl

RUN pecl install redis && docker-php-ext-enable redis

RUN pecl install mongodb && docker-php-ext-enable mongodb

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir -p /var/www/.composer/cache && \
    chown -R www-data:www-data /var/www/.composer

WORKDIR /var/www/html

USER www-data

EXPOSE 9000
CMD ["php-fpm", "-F"]
