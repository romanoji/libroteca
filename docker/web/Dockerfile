FROM php:7.1.8-apache

# Setup dependencies
RUN apt-get update \
 && apt-get install -y \
    curl \
    libpq-dev \
 && apt-get clean -y \
 && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-install \
    pdo \
    pdo_pgsql

# Set timezone
RUN echo "Europe/Warsaw" > /etc/timezone \
  && dpkg-reconfigure -f noninteractive tzdata

# Override PHP settings
COPY php/php.ini /usr/local/etc/php

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enabled rewrite module
RUN a2enmod rewrite

# Override default website configuration
COPY apache/vhost.conf /etc/apache2/sites-enabled/000-default.conf
RUN echo "ServerName libroteca.dev" >> /etc/apache2/apache2.conf
