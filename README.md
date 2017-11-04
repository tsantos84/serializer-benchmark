# Serializers Benchmarking

This project aims to compare the performance of the must used (except the last one) serialization libraries on PHP projects.

- [JMS Serializer](http://jmsyst.com/libs/serializer)
- [Symfony Serializer](https://symfony.com/doc/current/components/serializer.html)
- [TSantos Serializer](https://github.com/tsantos84/serializer)
- [SimpleSerializer](https://github.com/opensoft/simple-serializer/)

## Inspiration

I'm a heavy user of JMS Serializer library and I really like to use that library in my projects. Therefore, after profiling my RESTful application with [Blackfire](https://blackfire.io/),
I realized that JMS were consuming precious ~20ms of the request and it isn't not a good overhead just to transform objects to JSON representation. Following
this restlessness, I had the curiosity to profile the Symfony's serialization component also and now I have good insights about them.

I'm the author of TSantos Serializer and it was included in this benchmarking just to see if I'm going on the right way or not.

## Metodology

I've setup a simple benchmark application that shows the time consuming when serializing a simple object many times. Each execution loop through `n` interactions which means serialization of `n` instances of
the same classe.

To get a accurated result all serializers were optmized for production environment.

## Instalation

Clone this repository in your workspace

```bash
git clone https://github.com/tsantos84/serializers-benchmarking.git 
```

Install the application's dependencies including Symfony and JMS serializer components.

```bash
composer install -a --no-dev
```

## Execution

The benchmark application were executed in an official PHP docker image (PHP 7.1-cli). The results is directly influenced by the Docker host, so if you are running this script on
OS X you will see a very different result compared to Ubuntu for example, but the average response time between the components keep in the same proportion. (see results section)

You can run it by typing the following command in your terminal:

```bash
docker run --rm -it -v $(pwd):/opt -w /opt php:7.1-cli-alpine php app.php 100
```

## Results

I've got surprised when I saw the results because the JMS Serializer - the one I used to use - was the slowest serialization component which caught my attention 
exactly because I use it a lot in my personal and company projects.

| Vendor            | 1 Int. | 10 Int. | 50 Int. | 100 Int. | 200 Int. | 500 Int. | 1k Int. | 10k Int. | 50k Int. |
|-------------------|--------|---------|---------|----------|----------|----------|---------|----------|----------|
| JMS               | 0      | 1       | 4       | 11       | 20       | 53       | 101     | 1107     | 5244     |
| Symfony           | 0      | 0       | 3       | 7        | 16       | 39       | 72      | 786      | 3787     |
| TSantos           | 0      | 0       | 1       | 1        | 4        | 13       | 24      | 222      | 1157     |
| SimpleSerializer  | 0      | 0       | 0       | 0        | 1        | 2        | 5       | 48       | 266      |

As you can see, as the interactions is growing the JMS and Symfony will taking more time to serializer the objects. In big applications having milions of simultaneos access
it can be a considerable metric when choose such libraries. 

![New Relic Response Time](https://github.com/tsantos84/serializers-benchmarking/raw/master/img/benchmark.png "JMS, Symfony and TSantos comparison")

	As mentioned above, the Docker host can impact on the results. The above image shows the 
	interactions/response time when running in the same image but on an OS X host.

The following chart shows how the response time was improved drastically when I switch from JMS to TSantos Serializer.

![New Relic Response Time](https://github.com/tsantos84/serializers-benchmarking/raw/master/img/serialization-new-relic.png "API using TSantos serializer")

## Conclusion

After making this benchmark, I got inspired to write [my own serialization component](https://github.com/tsantos84/serializer) and the results are pointing me that in terms of performance,
I'm going to the right way. I have no intention to make the common mistake of [DRY](https://pt.wikipedia.org/wiki/Don%27t_repeat_yourself) but, in some cases, the performance is a requirement 
and those libraries can introduce some unnecessary overhead in your application and hence make your application be slow.

## TSantos Serializer

I'm writing a serialization library with a diferent approach compared to JMS and Symfony. Both two use lot of reflections to access the property values and make
lot of loops to interact through each property of each object that is being serialized. TSantos Serializer uses different way to serialize objects and at 
the same time keep the usefull features offerred by the others library.
