# ==========================
# Stage 1 - Composer
# ==========================
FROM php:8.3-cli AS composer

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-scripts

COPY . .

RUN composer dump-autoload --optimize
# ==========================
# Stage 2 - Frontend
# ==========================
#FROM node:22-alpine AS node

#WORKDIR /var/www/html

#COPY package*.json ./

#RUN npm ci

#COPY . .

#RUN npm run build

# ==========================
# Stage 3 - Production PHP
# ==========================
FROM php:8.3-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    nano \
    supervisor \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    libpq-dev \
    libssl-dev \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Configure GD
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    bcmath \
    exif \
    gd \
    intl \
    opcache \
    pcntl \
    pdo_mysql \
    zip


# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer /var/www/html /var/www/html




# Copy Vite production assets
#COPY --from=node /var/www/html/public/build ./public/build


COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

