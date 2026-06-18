FROM php:8.4-apache

# =========================
# SYSTEM DEPENDENCIES
# =========================
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        zip \
        gd \
        exif \
        bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# =========================
# COMPOSER
# =========================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =========================
# APACHE CONFIG
# =========================
RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# =========================
# WORKDIR
# =========================
WORKDIR /var/www/html

# =========================
# COPY PROJECT
# =========================
COPY . .

# =========================
# COMPOSER INSTALL
# =========================
RUN composer install --no-dev --optimize-autoloader --no-interaction || true

# =========================
# LARAVEL REQUIRED FOLDERS
# =========================
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# =========================
# PERMISSION FIX (IMPORTANT)
# =========================
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# safety tmp
RUN chmod 1777 /tmp
