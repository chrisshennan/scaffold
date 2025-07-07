FROM composer AS composer

ARG COMPOSER_ARG="--no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts"

COPY composer.* /app/
RUN composer install $COMPOSER_ARG

FROM php:8.4-fpm AS base

RUN apt-get update && apt-get install -y \
        libicu-dev \
        acl \
        procps \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install intl \
    && docker-php-ext-install opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

FROM base AS app

COPY . /app/
COPY --from=composer /app/vendor /app/vendor

RUN echo "APP_ENV=prod" > .env.local

RUN ./bin/console cache:clear --no-warmup

RUN HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\\  -f1` && \
    setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/log && \
    setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/log

RUN ./bin/console assets:install public
RUN ./bin/console importmap:install

RUN ./bin/console tailwind:build --minify
RUN ./bin/console asset-map:compile

FROM app AS app-dev

RUN echo "APP_ENV=dev" > .env.local

RUN apt-get update && apt-get install -y \
        git \
        zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');"

RUN mv composer.phar /usr/local/bin/composer

FROM caddy:2.9.1-builder-alpine AS caddy-builder

# 1) C tool‑chain + Brotli headers/libs
RUN apk add --no-cache build-base brotli-dev pkgconfig

# 2) Turn cgo ON (off by default in the builder image)
ENV CGO_ENABLED=1

RUN xcaddy build v2.10.0 \
    # Used for optimizing responses with Brotli compression
    --with github.com/dunglas/caddy-cbrotli@v1.0.1

FROM caddy:2.9.1-alpine AS app-caddy

RUN apk add --no-cache brotli

COPY --from=caddy-builder /usr/bin/caddy /usr/bin/caddy
COPY ./config/deploy/caddy /etc/caddy
COPY --from=app /app/public/ /app/public/
