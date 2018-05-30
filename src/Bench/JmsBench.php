<?php

namespace TSantos\Benchmark\Bench;

use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use TSantos\Benchmark\AbstractBench;
use TSantos\Benchmark\Person;

class JmsBench extends AbstractBench
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    public function init()
    {
        $this->serializer = SerializerBuilder::create()
            ->setPropertyNamingStrategy(new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy()))
            ->setDebug(false)
            ->setCacheDir('/tmp/serializer/cache/jms')
            ->addMetadataDir(__DIR__ . '/../Resources/mappings/jms', 'TSantos\\Benchmark')
            ->build();
    }

    protected function doBenchSerialize(array $objects)
    {
        $this->serializer->serialize($objects, 'json');
    }

    protected function doBenchDeserialize(string $content)
    {
        $this->serializer->deserialize($content, 'array<' . Person::class . '>', 'json');
    }
}
