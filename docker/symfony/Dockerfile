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

# Setup Xdebug
RUN pecl install xdebug-2.5.5 \
    # https://github.com/docker-library/php/issues/414
 && docker-php-ext-enable `echo xdebug-2.5.5 | sed -e "s/[^a-z ]//g"`
COPY php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install Composer
COPY setup.sh /usr/local/bin/setup.sh
RUN chmod +x /usr/local/bin/setup.sh && /usr/local/bin/setup.sh && rm /usr/local/bin/setup.sh

# Enabled rewrite module
RUN a2enmod rewrite

# Override default website configuration
COPY apache/vhost.conf /etc/apache2/sites-enabled/000-default.conf
RUN echo "ServerName libroteca.dev" >> /etc/apache2/apache2.conf
