FROM php:8.3-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    librdkafka-dev \
    supervisor \
    && docker-php-ext-install pdo pdo_pgsql pcntl \
    && pecl install redis && docker-php-ext-enable redis \
    && pecl install xdebug && docker-php-ext-enable xdebug \
    && pecl install rdkafka && docker-php-ext-enable rdkafka

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY ./docker/supervisor/kafka_orders.conf /etc/supervisor/conf.d/kafka_orders.conf
COPY ./docker/supervisor/laravel_worker.conf /etc/supervisor/conf.d/laravel_worker.conf
COPY ./docker/supervisor/php-fpm.conf /etc/supervisor/conf.d/php-fpm.conf

COPY ./docker/start-supervisor.sh /usr/local/bin/start-supervisor.sh
RUN chmod +x /usr/local/bin/start-supervisor.sh

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

CMD ["sh", "/usr/local/bin/start-supervisor.sh"]
