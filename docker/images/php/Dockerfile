FROM php:7.4-fpm-alpine
RUN apk add --update --no-cache --virtual htop \
    nmap \
    nmap-nping \
    tcpdump \
    strace \
    lsof \
    wget \
    zip \
    gnupg \
    mysql-client \
    libxml2-dev \
    libzip-dev \
    icu-libs \
    icu-dev \
    openssl-dev \
    && docker-php-ext-configure intl --enable-intl \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install soap \
    && docker-php-ext-install sockets \
    && docker-php-ext-install intl \
    && docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer