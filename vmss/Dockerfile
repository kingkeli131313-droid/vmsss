# Use an official production-ready PHP environment with Apache web server
FROM php:8.2-apache

# Install the PDO MySQL extension so your backend can talk to your database
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache URL rewriting modules if needed later
RUN a2enmod rewrite

# Copy your entire full-stack project structure into the web server directory
COPY . /var/www/html/

# Expose port 80 for public web traffic
EXPOSE 80

# Start the Apache web server infrastructure automatically
CMD ["apache2-foreground"]