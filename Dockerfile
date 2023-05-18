FROM alpine:latest


# laravel lumen required
RUN apk --no-cache add \
php8 \
php8-fpm \
php8-pdo \
php8-mbstring \
php8-openssl

# for code run smoothly
RUN apk --no-cache add \
php8-json \
php8-dom \
curl \
php8-curl \
php8-tokenizer

#for composer
RUN apk --no-cache add \
php8-phar \
php8-xml \
php8-xmlwriter

# if need composer to update plugin / vendor used
RUN php8 -r "copy('http://getcomposer.org/installer', 'composer-setup.php');" && \
php8 composer-setup.php --install-dir=/usr/bin --filename=composer && \
php8 -r "unlink('composer-setup.php');"

RUN apk --no-cache add git

