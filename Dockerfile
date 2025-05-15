FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
  git \
  unzip \
  libzip-dev \
  && docker-php-ext-install zip bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install

CMD ["./vendor/bin/phpunit"]