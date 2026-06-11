FROM php:8.2-apache

# Install required system libraries for PostgreSQL, then install the PHP drivers
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite
COPY . /var/www/html/
EXPOSE 80
CMD ["apache2-foreground"]
