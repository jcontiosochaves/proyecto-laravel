FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    unzip \
    && docker-php-ext-install pdo pdo_sqlite

WORKDIR /app
COPY . /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --prefer-dist --no-interaction --ignore-platform-reqs

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
