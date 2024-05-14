FROM php:8.3-fpm-alpine as base

RUN apk add $PHPIZE_DEPS linux-headers libzip-dev icu-dev libxml2-dev libpng-dev bash \
    && docker-php-ext-install \
      bcmath \
      calendar  \
      gd  \
      intl \
      opcache \
      pcntl \
      pdo_mysql \
      soap \
      zip \
    && pecl install \
      apcu \
    && docker-php-ext-enable \
      apcu

COPY php-fpm/php-fpm.conf /usr/local/etc/php-fpm.conf

FROM base as development

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN adduser -Ds /bin/sh app \
    && mkdir -p /app /home/app/.composer \
    && chown app /app /home/app/.composer

WORKDIR /app

RUN apk add \
      ca-certificates \
      gcompat \
      git \
      libc6-compat \
    && pecl install \
      pcov \
      xdebug \
    && docker-php-ext-enable \
      pcov \
      xdebug

COPY php-fpm/php.development.ini $PHP_INI_DIR/conf.d/php.ini
COPY php-fpm/apcu.ini $PHP_INI_DIR/conf.d/zz-apcu.ini
COPY php-fpm/xdebug.ini $PHP_INI_DIR/conf.d/zz-xdebug.ini

RUN php -v | head -n 1 | awk '{{print $2}}' >> /home/app/php-version \
    && cat /home/app/php-version

USER app
ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_CACHE_DIR=/home/app/.composer

FROM php:8.3-fpm-alpine as production

RUN adduser -Ds /bin/sh app \
    && mkdir -p /app \
    && chown app /app

WORKDIR /app

RUN apk add \
      icu \
      libpng \
      libxml2 \
      libzip

COPY --from=base /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=base $PHP_INI_DIR/conf.d/ $PHP_INI_DIR/conf.d/
COPY --from=base /usr/local/etc/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY php-fpm/php.production.ini $PHP_INI_DIR/conf.d/php.ini
COPY php-fpm/apcu.ini $PHP_INI_DIR/conf.d/zz-apcu.ini

RUN php -v | head -n 1 | awk '{{print $2}}' >> /home/app/php-version \
    && cat /home/app/php-version

USER app
ENV DOCUMENT_ROOT=/app/public

FROM production as web-production

USER root

RUN apk add nginx

COPY nginx/nginx.conf /etc/nginx/nginx.conf
COPY nginx/symfony.conf /etc/nginx/conf.d/default.conf

RUN mkdir /app/public && \
    chown -R app /var/lib/nginx

RUN cat /home/app/php-version

USER app
EXPOSE 8080
