FROM php:7.1-cli-alpine
RUN apk add --no-cache g++ make autoconf
RUN pecl install xdebug && docker-php-ext-enable xdebug
ENV PHP_IDE_CONFIG "serverName=serializers-benchmarking"

RUN printf '\n\
[Xdebug]\n\
xdebug.remote_enable = 1\n\
xdebug.remote_autostart = 1\n\
xdebug.profiler_enable = 0\n\
xdebug.remote_connect_back = 1\n' | tee -a /usr/local/etc/php/php.ini
