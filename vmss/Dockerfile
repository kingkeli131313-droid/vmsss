FROM php:8.2-apache

# 1. Install required system libraries for PostgreSQL and PHP drivers
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Enable Apache rewrite engine
RUN a2enmod rewrite

# 3. Hardcode modern fallback routing rules straight into Apache's default configuration
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
        \n\
        RewriteEngine On\n\
        RewriteCond %{REQUEST_FILENAME} !-f\n\
        RewriteCond %{REQUEST_FILENAME} !-d\n\
        RewriteRule ^ index.php [L]\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# 4. Copy project files and set web folder permissions
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
