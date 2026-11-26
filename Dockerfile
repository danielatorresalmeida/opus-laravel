# Railway container for OPUS (Laravel 13 / PHP 8.3).
# Keep secrets in Railway variables; do not copy a local .env into this image.
FROM php:8.3-apache

RUN apt-get update \
 && apt-get install -y --no-install-recommends \
      ca-certificates curl git unzip libcurl4-openssl-dev libicu-dev libonig-dev libzip-dev \
 && docker-php-ext-install -j"$(nproc)" curl intl mbstring opcache pdo_mysql zip \
 && a2enmod rewrite \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html

COPY . .
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
 && mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && php artisan storage:link

RUN printf 'Listen 8080\n' > /etc/apache2/ports.conf
COPY docker/apache-opus.conf /etc/apache2/sites-available/000-default.conf

ENV PORT=8080
EXPOSE 8080
CMD ["apache2-foreground"]
