FROM alpine:latest


# laravel lumen required
RUN apk --no-cache add \
php \
php-fpm \
php-pdo \
php-mbstring \
php-openssl

# for code run smoothly
RUN apk --no-cache add \
php-json \
php-dom \
curl \
php-curl \
php-tokenizer

#for composer
RUN apk --no-cache add \
php-phar \
php-xml \
php-xmlwriter

# if need composer to update plugin / vendor used
RUN php -r "copy('http://getcomposer.org/installer', 'composer-setup.php');" && \
php composer-setup.php --install-dir=/usr/bin --filename=composer && \
php -r "unlink('composer-setup.php');"

RUN apk --no-cache add git

