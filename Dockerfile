FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

RUN { \
    echo '[global]'; \
    echo 'error_log = /proc/self/fd/2'; \
    echo; \
    echo '[www]'; \
    echo 'access.log = /proc/self/fd/2'; \
    echo 'catch_workers_output = yes'; \
} > /usr/local/etc/php-fpm.d/docker.conf

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache

RUN chown -R www-data:www-data /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

CMD ["php-fpm", "-F"]