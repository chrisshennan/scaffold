FROM composer AS composer
COPY . /app/
RUN composer install --ignore-platform-reqs

FROM idlemoments/php:8.3-fpm-dev AS app

WORKDIR /app

COPY . /app/
COPY --from=composer /app/vendor /app/vendor

FROM app AS app-dev

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php \
RUN php -r "unlink('composer-setup.php');"

RUN mv composer.phar /usr/local/bin/composer