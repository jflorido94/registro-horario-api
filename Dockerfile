FROM php:8.1-fpm-alpine

RUN docker-php-ext-install pdo_mysql

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

# Instalar git
RUN apk update && apk add git nano

WORKDIR /var/www/html/registro

# Clonar el repositorio publico
RUN git clone https://gitlab.bmsl.es/jflorido/registro-horario-api.git .

RUN composer install --no-dev --ignore-platform-reqs --prefer-dist --no-interaction --no-progress --no-scripts

# Configurar Laravel Lumen
RUN cp .env.example .env

RUN php artisan migrate --seed --force

RUN php artisan jwt:secret

# Permisos de almacenamiento en caché
RUN chmod -R 777 storage bootstrap
