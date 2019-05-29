# Serializer Benchmark [![Build Status](https://travis-ci.org/tsantos84/serializer-benchmark.svg?branch=master)](https://travis-ci.org/tsantos84/serializers-benchmarking)

This project aims to compare the performance of some most used and few less known JSON serialization libraries for PHP.

- [JMS Serializer](http://jmsyst.com/libs/serializer)
- [Symfony Serializer](https://symfony.com/doc/current/components/serializer.html)
- [TSantos Serializer](https://github.com/tsantos84/serializer)
- [Zumba Json Serializer](https://github.com/zumba/json-serializer)
- [Nil Portugués](https://github.com/nilportugues/php-serializer)
- [Gson](https://github.com/tebru/gson-php)
- [Better Serializer](https://github.com/better-serializer/better-serializer)

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
php vendor/bin/phpbench run --warmup=1 --report=tsantos --group=serialize
```
### Vendors

It is possible to see all the serializer libraries available in this benchmark and its version:

```bash
php vendor/bin/phpbench vendors
```

### Benchmark Tool

This project was written based on [PHPBench](http://phpbench.readthedocs.io/en/latest/index.html). Please,
refer to its documentation page for further reading about all its runner options.

## Blackfire Integration

[Blackfire](https://blackfire.io/) is a excelent tool to profile PHP applications and helps you to discover some bottleneck points. This project allows you to run benchmarks and send the call-graph to Blackfire's server so you can see how each library works internally.

### Installation

In order to start using Blackfire, you first need to sign up on Blackfire.io and then you'll have access to your credentials.

#### Agent

Creates a new docker container with the Blackfire's Agent:

```bash
docker run -d \
  --name="blackfire" \
  -e BLACKFIRE_SERVER_ID={YOUR_BLACKFIRE_SERVER_ID_HERE} \
  -e BLACKFIRE_SERVER_TOKEN={YOUR_BLACKFIRE_SERVER_TOKEN_HERE} \
  blackfire/blackfire
```

#### PHP Executable

Create a custom PHP image with Blackfire extension installed and enabled:

```bash
cd /path/to/serializer-benchmark
docker build -t benchmark -f Dockerfile.blackfire .
```

#### Running the application

Now you can run the application using the PHP image create on step before:

```bash
docker run \
  --rm \
  -it \
  -v $(pwd):/opt \
  -w /opt \
  -e BLACKFIRE_CLIENT_ID={YOUR_BLACKFIRE_CLIENT_ID_HERE} \
  -e BLACKFIRE_CLIENT_TOKEN={YOUR_BLACKFIRE_CLIENT_TOKEN_HERE} \
  --link blackfire:blackfire \
  benchmark php vendor/bin/phpbench run --warmup=1 --report=tsantos --group=serialize --executor=blackfire
```

#### Docker Compose

Instead of running each container manually, you can use `docker-compose` to run the benchmarks. To accomplish this
you need to create a copy of the `docker-compose.yaml.dist` file:

```bash
cp docker-compose.yml.dist docker-compose.yml
```

and run one of the following commands:

```bash

# perform serialization benchmark
docker-compose run --rm bench_serialize

# perform deserialization benchmark
docker-compose run --rm bench_deserialize

# perform serialization benchmark with Blackfire enabled
docker-compose run --rm bench_serialize_blackfire \
    -e BLACKFIRE_SERVER_ID={YOUR_BLACKFIRE_SERVER_ID} \
    -e BLACKFIRE_SERVER_TOKEN={YOUR_BLACKFIRE_SERVER_TOKEN} \
    -e BLACKFIRE_CLIENT_ID={YOUR_BLACKFIRE_CLIENT_ID} \
    -e BLACKFIRE_CLIENT_TOKEN={YOU_BLACKFIRE_CLIENT_TOKEN}

# perform deserialization benchmark with Blackfire enabled
docker-compose run --rm bench_deserialize_blackfire \
    -e BLACKFIRE_SERVER_ID={YOUR_BLACKFIRE_SERVER_ID} \
    -e BLACKFIRE_SERVER_TOKEN={YOUR_BLACKFIRE_SERVER_TOKEN} \
    -e BLACKFIRE_CLIENT_ID={YOUR_BLACKFIRE_CLIENT_ID} \
    -e BLACKFIRE_CLIENT_TOKEN={YOU_BLACKFIRE_CLIENT_TOKEN}
```

As you have your own copy of the `docker-compose.yml` file, you can define those environment variables there
and save time when run the benchmarks with Blackfire enabled.

#### Note

By running the benchmark with Blackfire enabled you'll realize that the mean time will increase substantially. This behavior is expected because the Blackfire needs to introspect in your code and hence affects the benchmark metrics.

## Contribution

Want to see more libraries in this benchmark? You can easily add new benchmarks by implementing the
[BenchInterface](https://github.com/tsantos84/serializer-benchmark/blob/master/src/BenchInterface.php) interface
or extending the [AbstractBench](https://github.com/tsantos84/serializer-benchmark/blob/master/src/AbstractBench.php)
class which has a lot of help methods. Please, take a look at some of existing bench classes and you'll see how you can
write your own benchmark.
