FROM php:8.2-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    icu-dev \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    bash \
    curl \
    mysql-client

# Install PHP extensions
RUN docker-php-ext-install \
    intl \
    mysqli \
    gd \
    zip \
    mbstring

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Set entrypoint
CMD composer install && php spark serve --host 0.0.0.0
