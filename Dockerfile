FROM composer AS composer

ARG APP_ENV="prod"
ARG COMPOSER_ARG="--no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts"

COPY composer.* /app/
RUN composer install $COMPOSER_ARG

FROM php:8.3-fpm AS app

RUN apt-get update && apt-get install -y \
        libicu-dev \
        acl \
        procps \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install intl \
    && docker-php-ext-install opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY . /app/
COPY --from=composer /app/vendor /app/vendor

RUN echo "APP_ENV=prod" > .env.local

RUN ./bin/console cache:clear

RUN HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\\  -f1` && \
    setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/log && \
    setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/log

RUN ./bin/console assets:install public
RUN ./bin/console importmap:install

RUN ./bin/console tailwind:build --minify
RUN ./bin/console asset-map:compile

# Copy assets to shared folder so we can allow nginx access to them
COPY ./config/build/php-start-server.sh /start-server.sh
RUN chmod +x /start-server.sh

CMD ["/start-server.sh"]

FROM app AS app-dev

RUN echo "APP_ENV=dev" > .env.local

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php \
RUN php -r "unlink('composer-setup.php');"

RUN mv composer.phar /usr/local/bin/composer

FROM nginx:1.27.2 AS app-nginx

COPY ./config/docker/nginx/default.conf /etc/nginx/conf.d/default.conf

COPY --from=app /app/public/ /app/public/