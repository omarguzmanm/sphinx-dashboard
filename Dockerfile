FROM php:8.5-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    nano \
    build-essential \
    poppler-utils \
    npm \
    libpq-dev \
    postgresql-client \
    locales \
    zip \
    curl \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    vim \
    unzip \
    git \
    libxml2-dev \
    libxslt-dev \
    libonig-dev \
    imagemagick \
    libmagickwand-dev \
    zlib1g-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

ENV LANG=C.UTF-8

RUN npm install n -g && \
    n latest

RUN docker-php-ext-install soap xsl sockets
RUN docker-php-ext-install pdo_pgsql pgsql mbstring zip

# OPcache ya viene compilado en php:8.5-fpm, solo hay que configurarlo
# (ver php/opcache.ini y php/opcache.prod.ini). Es la diferencia entre compilar
# cada archivo PHP en cada petición y no hacerlo.
RUN docker-php-ext-install pcntl

# phpredis: sesiones, caché, colas y contadores en vivo salen de PostgreSQL y
# pasan a Redis (ver REDIS_CLIENT=phpredis en .env).
RUN pecl install redis && docker-php-ext-enable redis
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Add user for laravel application
ARG WWW_USER_ID
ARG WWW_GROUP_ID


RUN groupadd -g ${WWW_GROUP_ID} www
RUN useradd -u ${WWW_USER_ID} -ms /bin/bash -g www www

# Copy existing application directory contents
#COPY ./app/ /var/www

# Copy existing application directory permissions
COPY --chown=www:www ./app/ /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
