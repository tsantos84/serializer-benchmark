# Serializers Benchmarking [![Build Status](https://travis-ci.org/tsantos84/serializers-benchmarking.svg?branch=master)](https://travis-ci.org/tsantos84/serializers-benchmarking)

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

Each benchmark consists of `n` samples (100 by default) for serialization/deserialization of different instances of same
class (1 instance by default) and calculates average statistics. The amount of objects being processed can be overriden 
to check performance in more real-world-like conditions (e.g. serialize 10000 objects in array).

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
php app -i 200
```

If you don't have PHP of required version you may use suitable Docker PHP image (PHP 7.1-cli-alpine).

```bash
docker run --rm -it -v $(pwd):/opt -w /opt php:7.1-cli-alpine php app serialize
```

### Application parameters

There're 3 application commands available:
  - `serialize` - run serialization benchmark
  - `deserialize` - run deserialization benchmark
  - `vendors` - show list of available vendors, their capabilities and versions
 
Each of `serialize` and `deserialize` commands has following options:
  - `--samples` or `-s` - to define how many samples will be taken (default: 100)
  - `--batch-count` or `-b` - to define how many objects to be processed per sample (default: 1)
  - `--exclude` or `-e` - to exclude a vendor from benchmark (multiple values allowed)


## Results
### Serialization

| Vendor            | 10 Obj.| 100 Obj. | 1k Obj. | 10k Obj. |
|-------------------|--------|----------|---------|----------|
| JMS               | 0.52   | 4.51     | 45.86   | 495.83   |
| Symfony           | 0.59   | 4.43     | 42.89   | 462.02   |
| TSantos           | 0.18   | 1.6      | 18.18   | 184.14   |
| SimpleSerializer  | 0.32   | 3.17     | 34.71   | 377.3    |

### Deserialization
Sadly, TSantos serializer is only capable of serialization and has no means of deserialization, yet.

| Vendor            | 10 Obj.| 100 Obj. | 1k Obj. | 10k Obj. |
|-------------------|--------|----------|---------|----------|
| JMS               | 0.64   | 4.28     | 41.16   | 459.64   |
| Symfony*          | 0.1    | 0.84     | 10.59   | 154.41   |
| SimpleSerializer  | 0.12   | 0.59     | 6.57    | 96.85    |

* Symfony serializer handles complex object quite poorly with default denormalizers, so creating a custom denormalizer,
tailored specifically to your business domain, is a [good solution](https://thomas.jarrand.fr/blog/serialization/). It
allows Symfony serializer to skip all the magic and do what exactly required, which on one hand requires quite a bit of
[additional coding](blob/master/src/Unserialize/Symfony/PersonDenormalizer.php), but on the other hand gives performance boost.  

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
