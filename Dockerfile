FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
  git \
  unzip \
  libzip-dev \
  && docker-php-ext-install zip bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock* ./

RUN composer install --no-scripts --no-autoloader

COPY . .

RUN composer dump-autoload --optimize

CMD ["./vendor/bin/phpunit"]