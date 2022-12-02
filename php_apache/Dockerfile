FROM php:8.1-apache
# MAINTAINER Engineering Sardegna Srl <esa.portal-vas@eng.it>

# Install PHP extensions
RUN apt-get update && apt-get install --no-install-recommends -y \
    cron \
    libc-client-dev \
    libicu-dev \
    libkrb5-dev \
    libmcrypt-dev \
    libssl-dev \
    libjpeg-dev \
    libpng-dev \
    libzip-dev \
    libmemcached-dev \
    libonig-dev \
    openssl \
    unzip \
    zip \
    vim \
    curl \
    git 
RUN docker-php-ext-configure imap --with-imap --with-imap-ssl --with-kerberos \
    && docker-php-ext-install opcache imap intl mbstring  mysqli pdo pdo_mysql  gd bcmath
# mcrypt zip
#apz  
# RUN pecl install mongodb 
 #&& docker-php-ext-enable mongodb

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

VOLUME /var/www/html

# Copy init scripts and custom .htaccess
COPY symfony-php.ini /usr/local/etc/php/conf.d/symfony-php.ini
COPY symfony.conf /etc/apache2/sites-available/000-default.conf
# Enable Apache Rewrite Module
RUN a2enmod rewrite

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install symfony-cli
#RUN ../prvapi/deploy_stage.sh

WORKDIR /var/www/html
