# Set source
FROM php:7.2-fpm

# Set user/group
ENV USER=endemic
ENV GROUP=endemic

MAINTAINER Dima Denisov <mail@endemic.ru>

RUN groupadd -g 1000 ${USER}
RUN useradd -d /home/${USER} -s /bin/bash -u 1000 -g 1000 ${USER}

# User and group own home directory
RUN mkdir /home/${USER}
RUN chown -R ${USER}:${GROUP} /home/${USER}

# Install apcu
RUN pecl install apcu

# Install php
RUN apt-get update && apt-get install -y \
        curl \
        wget \
        git \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libzip-dev \
        libmcrypt-dev \
    && docker-php-ext-install -j$(nproc) iconv mbstring mysqli pdo_mysql zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add custom ini to php
ADD php.ini /usr/local/etc/php/conf.d/40-custom.ini

# Create storage folder
RUN mkdir /var/www/storage \
    && chown -R ${USER}:${GROUP} /var/www/storage

# Set working dir
WORKDIR /var/www

# Set user
USER ${USER}

# start php-fpm
CMD ["php-fpm"]