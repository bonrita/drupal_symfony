FROM php:7.1.9-apache

LABEL maintainer="Bona"

COPY .docker/php/php.ini /usr/local/etc/php/
COPY . /var/www/html
COPY .docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY composer-installer.sh /usr/local/bin/composer-installer

# Install any custom system requirements here
# https://codereviewvideos.com/course/docker-tutorial-for-beginners/video/docker-php-7-tutorial-7-7-1-and-higher
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    curl \
    libicu-dev \
    libmemcached-dev \
    libz-dev \
    libpq-dev \
    libjpeg-dev \
    libpng12-dev \
    libfreetype6-dev \
    libssl-dev \
    libmcrypt-dev \
    libxml2-dev \
    libbz2-dev \
    libjpeg62-turbo-dev \
    php-pear \
    curl \
    git \
    subversion \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && docker-php-ext-install opcache \
    && pecl install xdebug-2.5.1 \
    && docker-php-ext-enable xdebug \
    && a2enmod rewrite

COPY .docker/php/xdebug-dev.ini /usr/local/etc/php/conf.d/xdebug-dev.ini


# Install composer
RUN apt-get -yqq update \
    && apt-get -yqq install --no-install-recommends unzip \
    && chmod +x /usr/local/bin/composer-installer \
    && composer-installer \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer \
    && composer --version

RUN chown -R www-data:www-data /var/www/html

# Alias the console.
RUN echo 'alias sf="php bin/console"' >> ~/.bashrc

WORKDIR /tmp
ADD composer.json composer.lock /tmp/
RUN mkdir -p database/seeds \
    mkdir -p database/factories \
    && composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    && rm -rf composer.json composer.lock auth.json\
    database/ vendor/

WORKDIR /var/www/html

RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist
