# Set master image
FROM --platform=linux/amd64 php:7.4-fpm-alpine3.14
# FROM alpine:3.9.2


# Copy composer.lock and composer.json
# COPY composer.lock composer.json /var/www/html/

# Set working directory
WORKDIR /var/www/html

RUN apt-get update -y && apt-get upgrade -y;

# Install Additional dependencies
RUN apk add --update --no-cache \
    $PHPIZE_DEPS \
    build-base \
    curl \
    freetype-dev \
    git \
    imagemagick \
    imagemagick-dev \
    imagemagick-libs \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    openssh-client \
    php7 \
    php7-bcmath \
    php7-common \
    php7-dom \
    php7-exif \
    php7-fpm \
    php7-gd \
    php7-imagick \
    php7-intl \
    php7-json \
    php7-mbstring \
    php7-mcrypt \
    php7-mysqli \
    php7-opcache \
    php7-openssl \
    php7-pcntl \
    php7-pdo \
    php7-pdo_mysql \
    php7-phar \
    php7-session \
    php7-simplexml \
    php7-soap \
    php7-tokenizer \
    php7-xml \
    php7-xsl \
    php7-zip \
    php7-zlib\
    shadow \
    sqlite \
    vim \
    nano \
    wget \
    xvfb \
    qt5-qtbase-dev \
    ttf-freefont \
    fontconfig \
    dbus \
    wget \
    dpkg-dev \
    zlib-dev \
        ttf-freefont \
        fontconfig \
        libxrender-dev \
        gettext \
        gettext-dev \
        libxml2-dev \
        gnu-libiconv-dev \
        wkhtmltopdf \
    && docker-php-ext-install pdo pdo_mysql soap zip \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
	&& docker-php-ext-install gd \
	&& docker-php-ext-install zip \
    && docker-php-ext-enable pdo_mysql gd soap zip

RUN apk --update add wget \
  openssl \
  openssl-dev \
  libxrender-dev

RUN apk add nginx-mod-http-headers-more



# Install PHP Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Remove Cache
RUN rm -rf /var/cache/apk/*

# Add UID '1000' to www-data
RUN usermod -u 1000 www-data



# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/html

# Change current user to www
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
