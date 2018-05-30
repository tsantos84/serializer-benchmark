# Serializers Benchmarking [![Build Status](https://travis-ci.org/tsantos84/serializers-benchmarking.svg?branch=master)](https://travis-ci.org/tsantos84/serializers-benchmarking)

This project aims to compare the performance of some most used and few less known JSON serialization libraries for PHP.

- [JMS Serializer](http://jmsyst.com/libs/serializer)
- [Symfony Serializer](https://symfony.com/doc/current/components/serializer.html)
- [TSantos Serializer](https://github.com/tsantos84/serializer)

## Inspiration

This benchmark attempts to compare serialization libraries performance-wise. All of the libraries have their own features,
support different formats, added magic and other stuff, but for the sake of simplicity it was decided to simplify sample data
to fit all of them.

The [core of benchmarking set](https://github.com/tsantos84/serializers-benchmarking) was implemented by [Tales Santos](https://github.com/tsantos84), the author of
[TSantos serializer](https://github.com/tsantos84/serializer).

## Instalation

### Clone this repository in your workspace

```bash
git clone https://github.com/tsantos84/serializers-benchmarking.git
```

### Install the application's dependencies

Using system installed composer

```bash
composer install -a --no-dev
```

**or** using composer in docker container:

```bash
docker run --rm --interactive --tty -v $(pwd):/app composer install -a --no-dev
```

## Execution

The benchmark application can be executed as is with PHP 7.1 and above.

```bash
php vendor/bin/phpbench run --warmup=1 --report=tsantos
```

If you don't have PHP of required version you may use suitable Docker PHP image (PHP 7.1-cli-alpine).

```bash
docker run --rm -it -v $(pwd):/opt -w /opt php:7.1-cli-alpine php vendor/bin/phpbench run --warmup=1 --report=tsantos --group=serialize
```

### Application parameters

There're 2 available benchmark groups:
  - `serialize` - run serialization benchmark only
  - `deserialize` - run deserialization benchmark only

```bash
php vendor/bin/phpbench run --warmup=1 --report=tsantos --groups=serialize
```

### Benchmark Tool

This project was written based on [PHPBench](http://phpbench.readthedocs.io/en/latest/index.html). Please,
refer to its documentation page for further reading about all its runner options.
