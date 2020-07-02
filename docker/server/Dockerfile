FROM php:7.2-apache
COPY ./public /var/www/html/


# Prepare APT for package installation
RUN apt update && apt upgrade -y

# Install required extensions
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install fileinfo

# Copy config files
COPY ./docker/server/httpd.conf /etc/apache2/httpd.conf
COPY ./docker/server/mod_rewrite.load /etc/apache2/mods-enabled/mod_rewrite.load
COPY ./docker/server/vhost-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./docker/server/dir-index.conf /etc/apache2/mods/enabled/dir.conf

# Configure PHP
COPY ./docker/server/php.ini /usr/local/etc/php/php.ini

# Copy DSJAS config file
COPY ./docker/server/docker-config.ini /var/www/html/Config.ini

# Make sure the server can write and read the directory
RUN chown -R www-data:www-data /var/www

EXPOSE 80