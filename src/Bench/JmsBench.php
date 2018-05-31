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

    public function bootstrap(): void
    {
        $this->serializer = SerializerBuilder::create()
            ->setPropertyNamingStrategy(new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy()))
            ->setDebug(false)
            ->setCacheDir('/tmp/serializer/cache/jms')
            ->addMetadataDir(__DIR__ . '/../Resources/mappings/jms', 'TSantos\\Benchmark')
            ->build();
    }

    protected function doBenchSerialize(array $objects): void
    {
        $this->serializer->serialize($objects, 'json');
    }

    protected function doBenchDeserialize(string $content): void
    {
        $this->serializer->deserialize($content, 'array<' . Person::class . '>', 'json');
    }

    public function getName(): string
    {
        return 'JMS';
    }

    public function getPackageName(): string
    {
        return 'jms/serializer';
    }
}
