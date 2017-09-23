FROM php:7.1.8-apache

# Setup dependencies
RUN \
    APT_PACKAGES="vim curl libpq-dev"; \
    PHP_EXTENSIONS="pdo pdo_pgsql"; \
    apt-get update \
 && apt-get install -y $APT_PACKAGES \
 && apt-get clean -y \
 && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-install $PHP_EXTENSIONS

# Set timezone
RUN echo "Europe/Warsaw" > /etc/timezone \
  && dpkg-reconfigure -f noninteractive tzdata

# Override PHP settings
COPY php/php.ini /usr/local/etc/php

# Enabled rewrite module
RUN a2enmod rewrite

# Override default website configuration
COPY apache/vhost.conf /etc/apache2/sites-enabled/000-default.conf
RUN echo "ServerName libroteca.dev" >> /etc/apache2/apache2.conf

# https://stackoverflow.com/questions/44716612/docker-php-permissions
# https://github.com/docker-library/php/issues/222
RUN usermod -u 1000 www-data