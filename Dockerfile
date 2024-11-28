FROM php:8.3-cli-alpine

RUN apk update && \
    apk add --no-cache \
    bash \
    curl \
    git \
    php83-pdo_mysql \
    php83-mbstring \
    php83-xml \
    php83-openssl \
    php83-phar \
    php83-zip \
    php83-dom \
    php83-xmlreader \
    php83-xmlwriter \
    php83-tokenizer \
    php83-simplexml \
    php83-pcntl \
    php83-curl \
    php83-iconv

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www/html

EXPOSE 8000

CMD ["bash"]
