FROM php:7.4-fpm-buster
SHELL ["/bin/bash", "-oeux", "pipefail", "-c"]

# timezone environment
ENV TZ=Asia/Tokyo \
  # locale
  LANG=en_US.UTF-8 \
  LANGUAGE=en_US:en \
  LC_ALL=en_US.UTF-8 \
  # composer environment
  COMPOSER_ALLOW_SUPERUSER=1 \
  COMPOSER_HOME=/composer \
  # Laravel environment
  DB_CONNECTION=mysql \
  DB_HOST=db \
  DB_PORT=3306 \
  DB_DATABASE=laravel_local \
  DB_USERNAME=phper \
  DB_PASSWORD=secret \
  LOG_CHANNEL=stderr

COPY --from=composer:1.10 /usr/bin/composer /usr/bin/composer

RUN apt-get update && \
  apt-get -y install git libicu-dev libonig-dev libzip-dev unzip locales zlib1g-dev libpng-dev libjpeg62-turbo-dev && \
  apt-get clean && \
  rm -rf /var/lib/apt/lists/* && \
  locale-gen en_US.UTF-8 && \
  localedef -f UTF-8 -i en_US en_US.UTF-8 && \
  mkdir /var/run/php-fpm && \
  apt-get update && apt-get install -y imagemagick libmagickwand-dev && \
  pecl install imagick && \
  docker-php-ext-enable imagick && \
  docker-php-ext-configure gd --with-jpeg && \
  docker-php-ext-install -j$(nproc) gd && \
  docker-php-ext-install intl pdo_mysql zip bcmath && \
  docker-php-ext-install exif && \
  composer config -g process-timeout 3600 && \
  composer config -g repos.packagist composer https://packagist.org && \
  composer global require hirak/prestissimo

COPY ./php-fpm.d/zzz-www.conf /usr/local/etc/php-fpm.d/zzz-www.conf
COPY ./php.ini /usr/local/etc/php/php.ini

WORKDIR /work/backend
