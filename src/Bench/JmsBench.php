<?php

namespace TSantos\Benchmark\Bench;

use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Metadata\Cache\PsrCacheAdapter;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use TSantos\Benchmark\AbstractBench;

/**
 * Class JmsBench
 * @package TSantos\Benchmark\Bench
 */
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
            ->setMetadataCache(new PsrCacheAdapter('JMSMetadata', new ApcuAdapter()))
            ->build();
    }

    protected function doBenchSerialize(array $objects): void
    {
        $this->serializer->serialize($objects, 'json');
    }

    protected function doBenchDeserialize(string $content, string $type): void
    {
        $this->serializer->deserialize($content, 'array<' . $type . '>', 'json');
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
