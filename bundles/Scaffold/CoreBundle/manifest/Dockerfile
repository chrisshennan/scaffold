FROM composer AS composer

ARG COMPOSER_ARG="--no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts"

COPY composer.* /app/

# Copy the bundles directory to the composer stage as it's a local path repository
COPY bundles /app/bundles

RUN composer install $COMPOSER_ARG

FROM php:8.4-fpm AS base

# Install required PHP extensions and dependencies for the application:
# - libicu-dev: Required for PHP intl extension
# - acl: For setting filesystem permissions
# - procps: For process management utilities
# - libmariadb-dev: MySQL/MariaDB development files
# Then clean up to reduce image size
RUN apt-get update \
    && apt-get install -y libicu-dev acl procps libmariadb-dev \
    && docker-php-ext-install pdo pdo_mysql intl opcache \
    && apt-get autoremove -y \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

FROM base AS app

COPY config/deploy/php/scaffold-custom.ini /usr/local/etc/php/conf.d/scaffold-custom.ini

COPY . /app/
COPY --from=composer /app/vendor /app/vendor

RUN echo "APP_ENV=prod" > .env.local

RUN mkdir --parents var/cache
RUN mkdir --parents var/log

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

# Install composer programmatically - https://getcomposer.org/doc/faqs/how-to-install-composer-programmatically.md
RUN EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')" \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")" \
    \
    if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ] \
    then \
        >&2 echo 'ERROR: Invalid installer checksum' \
        rm composer-setup.php \
        exit 1 \
    fi

RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer --quiet \
    && rm composer-setup.php

FROM caddy:2.10.0-builder-alpine AS caddy-builder

# 1) C tool‑chain + Brotli headers/libs
RUN apk add --no-cache build-base brotli-dev pkgconfig

# 2) Turn cgo ON (off by default in the builder image)
ENV CGO_ENABLED=1

# 3) Build Caddy with the required plugins
RUN xcaddy build v2.10.0 \
    # Used for optimizing responses with Brotli compression
    --with github.com/dunglas/caddy-cbrotli@v1.0.1

FROM caddy:2.10.0-alpine AS app-caddy

RUN apk add --no-cache brotli

COPY --from=caddy-builder /usr/bin/caddy /usr/bin/caddy
COPY ./config/deploy/caddy /etc/caddy
COPY --from=app /app/public/ /app/public/

FROM app-caddy AS app-caddy-dev
COPY ./config/docker/caddy /etc/caddy
