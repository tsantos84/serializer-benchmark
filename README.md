# Serializer Benchmark [![Build Status](https://travis-ci.org/tsantos84/serializer-benchmark.svg?branch=master)](https://travis-ci.org/tsantos84/serializers-benchmarking)

This project aims to compare the performance of some most used and few less known JSON serialization libraries for PHP.

- [JMS Serializer](http://jmsyst.com/libs/serializer)
- [Symfony Serializer](https://symfony.com/doc/current/components/serializer.html)
- [TSantos Serializer](https://github.com/tsantos84/serializer)
- [Zumba Json Serializer](https://github.com/zumba/json-serializer)
- [Nil Portugués](https://github.com/nilportugues/php-serializer)

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
### Vendors

It is possible to see all the serializer libraries available in this benchmark and its version:

```bash
php vendor/bin/phpbench vendors
```

### Benchmark Tool

This project was written based on [PHPBench](http://phpbench.readthedocs.io/en/latest/index.html). Please,
refer to its documentation page for further reading about all its runner options.

## Contribution

Want to see more libraries in this benchmark? You can easily add new benchmarks by implementing the 
[BenchInterface](https://github.com/tsantos84/serializer-benchmark/blob/master/src/BenchInterface.php) interface 
or extending the [AbstractBench](https://github.com/tsantos84/serializer-benchmark/blob/master/src/AbstractBench.php)
class which has a lot of help methods. Please, take a look at some of existing bench class and you'll see how you can 
write your own benchmark. 
