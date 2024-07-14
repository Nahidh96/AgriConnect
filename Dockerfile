FROM php:apache

# Install pdo_mysql extension
RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql

# Copy custom php.ini
COPY php.ini /usr/local/etc/php/php.ini
