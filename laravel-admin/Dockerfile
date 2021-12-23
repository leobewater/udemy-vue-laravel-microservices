FROM php:7.4.12-zts-buster

RUN apt-get update -y \
  && apt-get install -y nano nodejs yarn curl openssl zip unzip git telnet iputils-ping

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
&& ln -s $(composer config --global home) /root/composer
ENV PATH=$PATH:/root/composer/vendor/bin COMPOSER_ALLOW_SUPERUSER=1

RUN pecl install redis-5.1.1 \
    && docker-php-ext-enable redis

RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /app
COPY . .
COPY opcache.ini //usr/local/etc/php/conf.d/opcache.ini
RUN composer install

CMD php artisan serve --host=0.0.0.0
EXPOSE 8000
