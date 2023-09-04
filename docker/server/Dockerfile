FROM php:7.2-apache


# Prepare APT for package installation
RUN apt update && apt upgrade -y

# Install zlib for php extension
RUN apt install zlib1g zlib1g-dev libicu-dev -y

# Install required extensions
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install fileinfo
RUN docker-php-ext-install zip
RUN docker-php-ext-install intl

# Copy config files
COPY ./docker/server/httpd.conf /etc/apache2/httpd.conf
COPY ./docker/server/mod_rewrite.load /etc/apache2/mods-enabled/mod_rewrite.load
COPY ./docker/server/mod_headers.load /etc/apache2/mods-enabled/mod_headers.load
COPY ./docker/server/vhost-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./docker/server/dir-index.conf /etc/apache2/mods/enabled/dir.conf

# Configure PHP
COPY ./docker/server/php.ini /usr/local/etc/php/php.ini

# Copy source tree
COPY ./public /var/www/html/

# Configure DSJAS
COPY ./scripts/install/Config.ini /var/www/html/Config.ini
COPY ./scripts/install/themeConfig.ini /var/www/html/admin/site/UI/config.ini
COPY ./scripts/install/moduleConfig.ini /var/www/html/admin/site/modules/config.ini
COPY ./scripts/install/extConfig.ini /var/www/html/admin/site/extensions/config.ini

# Sed magic to skip install process
RUN sed -i '/\[setup\]/,/database_installed/ s/\(\S\+\)[0-9]\+\("\)/\11\2/g' /var/www/html/Config.ini

# Make sure the server can write and read the directory
RUN chown -R www-data:www-data /var/www

# Delete developer folders/files
RUN rm -rf /var/www/html/admin/site/UI/test_theme
RUN rm -rf /var/www/html/admin/site/modules/example

EXPOSE 80
