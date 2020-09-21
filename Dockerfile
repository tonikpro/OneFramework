FROM php:7.4.6-fpm-alpine3.11
RUN apk add --no-cache \
    autoconf \
    curl \
    g++ \
    gcc \
    git \
    libtool \
    make \
    tar \
    freetype-dev \
    libjpeg-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    postgresql-dev \
    zip \
    zlib-dev \
    unzip \
    wget \
    supervisor \ 
    linux-headers \
    oniguruma-dev \
    libzip-dev \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \ 
    && pecl install redis mcrypt-1.0.3 grpc protobuf \
    && docker-php-ext-configure gd \
    && docker-php-ext-configure pgsql \
    && docker-php-ext-configure zip \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql pdo_mysql mbstring tokenizer opcache exif zip \
    && docker-php-ext-enable redis mcrypt grpc protobuf 
    

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin 

# RUN mkdir /var/log/php \
#     && rm -rf /usr/local/etc/php-fpm.d/*



RUN mkdir /var/www/html/app
ADD . / /var/www/html/app/
RUN cd /var/www/html/app/ && composer install

WORKDIR /var/www/html/app
EXPOSE 9000
CMD ["php-fpm"]