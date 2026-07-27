# Base stage (production)
FROM php:8.4-apache-bullseye AS base

# Install necessary PHP extensions
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql intl zip

# Enable Apache mod_rewrite (required for Symfony routing)
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Copy Composer from an official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Fix Composer git safety
RUN git config --global --add safe.directory /var/www/html

# Ensure build runs in production mode
ENV APP_ENV=prod

# Install dependencies without dev packages (production optimized)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Set correct permissions
RUN mkdir -p /var/www/html/var \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/var

# Update DocumentRoot to point to Symfony public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Add ServerName to prevent warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Add wait-for-it.sh and entrypoint script
COPY wait-for-it.sh /usr/local/bin/wait-for-it.sh
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/wait-for-it.sh /usr/local/bin/docker-entrypoint.sh

COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# Use entrypoint to wait for DB
ENTRYPOINT ["docker-entrypoint.sh"]

# Start Apache
CMD ["apache2-foreground"]


# Dev stage (extends base, adds Xdebug)
FROM base AS dev

ENV APP_ENV=dev

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
COPY docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Reinstall with dev packages
RUN composer install --no-interaction
