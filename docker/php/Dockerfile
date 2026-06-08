FROM php:8.3-fpm

# Install dependencies + Tambahan FFmpeg untuk Transcoding WebM
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    mariadb-client \
    ffmpeg \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    zip \
    exif \
    pcntl \
    intl

# 🔥 SAMAKAN UID & GID DENGAN HOST (bahri48 = 1000)
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www