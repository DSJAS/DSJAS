FROM php:7.2-apache


# Prepare APT for package installation
RUN apt update && apt upgrade -y

# Install required extensions
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install fileinfo

# Install developer tools
RUN apt install bash -y
RUN apt install git -y
RUN apt install python3 -y
RUN apt install gnupg2 -y
RUN apt install vim -y

# Copy config files
COPY ./docker/server/httpd.conf /etc/apache2/httpd.conf
COPY ./docker/server/mod_rewrite.load /etc/apache2/mods-enabled/mod_rewrite.load
COPY ./docker/server/vhost-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./docker/server/dir-index.conf /etc/apache2/mods/enabled/dir.conf

# Configure PHP
COPY ./docker/server/php-dev.ini /usr/local/etc/php/php.ini

# Make sure the server can write and read the directory
RUN chown -R www-data:www-data /var/www


EXPOSE 80