FROM php:7.2-cli-alpine

ENV BLACKFIRE_CLIENT_ID=da4bcf60-5310-424f-895c-2e6c0ae77f86
ENV BLACKFIRE_CLIENT_TOKEN=3f27f7c88e6f52b1ab2692c6ef252f7b45e590e7c608bb3ea0b2af9bd81bb6a3

RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/alpine/amd64/$version \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp \
    && mv /tmp/blackfire-*.so $(php -r "echo ini_get('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini

WORKDIR /opt/serializer-benchmark
