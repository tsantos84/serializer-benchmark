# Serializers Benchmarking

This project aims to compare the performance of some most used and few less known JSON serialization libraries for PHP.

- [JMS Serializer](http://jmsyst.com/libs/serializer)
- [Symfony Serializer](https://symfony.com/doc/current/components/serializer.html)
- [TSantos Serializer](https://github.com/tsantos84/serializer)
- [SimpleSerializer](https://github.com/opensoft/simple-serializer/)

## Inspiration

This benchmark attempts to compare serialization libraries performance-wise. All of the libraries have their own features,
support different formats, added magic and other stuff, but for the sake of simplicity it was decided to simplify sample data
to fit all of them. Each benchmarked sample includes verification code, which checks wether serialization/deserialization
result is actually valid.

The [core of benchmarking set](https://github.com/tsantos84/serializers-benchmarking) was implemented by [Tales Santos](https://github.com/tsantos84), the author of 
[TSantos serializer](https://github.com/tsantos84/serializer).

## Metodology

Each benchmark consists of `n` iterations for serialization/deserialization of different instances of same class.
It is planned to expand test set to include batch processing (e.g. 1 iteration for 1000 objects in array).

To get a accurate result all serializers were optmized for production environment.

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
php ./app.php 100
```

If you don't have PHP of required version you may use suitable Docker PHP image (PHP 7.1-cli-alpine).

```bash
docker run --rm -it -v $(pwd):/opt -w /opt php:7.1-cli-alpine php app.php 100
```

## Results
### Serialization

| Vendor            | 1 Int. | 100 Int. | 500 Int. | 1k Int. | 10k Int. | 50k Int. |
|-------------------|--------|----------|----------|---------|----------|----------|
| JMS               | 0      | 6        | 30       | 53      | 555      | 2660     |
| Symfony           | 0      | 5        | 22       | 44      | 446      | 2228     |
| SimpleSerializer  | 0      | 4        | 15       | 32      | 377      | 1666     |
| TSantos           | 0      | 1        | 8        | 16      | 191      | 942      |

### Deserialization
Sadly, TSantos serializer is only capable of serialization and has no means of deserialization, yet.

| Vendor            | 1 Int. | 100 Int. | 500 Int. | 1k Int. | 10k Int. | 50k Int. |
|-------------------|--------|----------|----------|---------|----------|----------|
| JMS               | 0      | 5        | 25       | 48      | 502      | 2463     |
| Symfony           | 0      | 4        | 18       | 39      | 358      | 1812     |
| SimpleSerializer  | 0      | 2        | 22       | 45      | 437      | 2190     |

## Development

For easy debug use Dockerfile from this repository. Just do:

```bash
docker build -t php:7.1-cli-alpine-xdebug .
```

to build a minimal image that contains php7.1 with xdebug installed and configured for debugging.
The image already contains PHP_IDE_CONFIG environment variable setting `serverName` to `serializers-benchmarking`,
but you'll have to pass in the XDEBUG_CONFIG variable, when running, similar to this:

```bash
docker run --rm -it -v $(pwd):/opt -w /opt -e XDEBUG_CONFIG="remote_host=172.17.0.1" php:7.1-cli-alpine-xdebug php app.php 1
```

Use your host IP address as `remote_host` value. You can find it using, for example, following command:

```bash
ifconfig docker0 | sed -En 's/127.0.0.1//;s/.*inet (addr:)?(([0-9]*\.){3}[0-9]*).*/\2/p'
```
