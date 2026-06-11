FROM php:8.2-apache

# 1. Install required system libraries for PostgreSQL and PHP drivers
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Enable Apache rewrite engine
RUN a2enmod rewrite

# 3. Force Apache to allow .htaccess routing overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# 4. Copy project files and set standard web permissions
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
