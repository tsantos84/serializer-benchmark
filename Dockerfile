FROM php:7.2-cli-alpine

RUN docker-php-ext-install opcache

WORKDIR /opt/serializer-benchmark
