FROM php:8.0.6-fpm-alpine AS app-test

RUN apk --update add wget \
    curl \
    git \
    grep \
    build-base \
    libmemcached-dev \
    libmcrypt-dev \
    libxml2-dev \
    imagemagick-dev \
    pcre-dev \
    libtool \
    make \
    autoconf \
    zlib-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    g++ \
    cyrus-sasl-dev \
    libgsasl-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    openssh && \
    rm -rf /var/cache/apk/*

RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd && \
    docker-php-ext-configure intl && \
    pecl install pcov && \
    docker-php-ext-enable pcov && \
    docker-php-ext-install opcache \
    mysqli \
    gd \
    pdo \
    pdo_mysql \
    intl \
    zip \
    exif \
    bcmath

# install composer 2
COPY --from=composer:2.1.3 /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_HOME /var/www/.composer

# install Node JS
RUN apk add --no-cache nodejs-current \
    npm


COPY ./php.ini $PHP_INI_DIR/conf.d/local.ini
COPY ./php-fpm.conf /usr/local/etc/php-fpm.d/zzz-docker-overrides.conf

WORKDIR /var/www/html

FROM nginx:1.15-alpine AS web-test
COPY ./nginx.conf /etc/nginx/conf.d/default.conf
WORKDIR /var/www/html/public
COPY ./public ./
