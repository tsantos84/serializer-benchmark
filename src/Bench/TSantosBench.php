<?php

namespace TSantos\Benchmark\Bench;

use Metadata\Driver\FileLocator;
use Symfony\Component\Filesystem\Filesystem;
use TSantos\Benchmark\AbstractBench;
use TSantos\Benchmark\Person;
use TSantos\Serializer\Metadata\Driver\YamlDriver;
use TSantos\Serializer\SerializerBuilder;
use TSantos\Serializer\SerializerInterface;

/**
 * Class TSantosBench
 *
 * @author Tales Santos <tales.augusto.santos@gmail.com>
 */
class TSantosBench extends AbstractBench
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var Filesystem
     */
    protected $fs;

    public function bootstrap(): void
    {
        $fileLocator = new FileLocator(['TSantos\Benchmark' => $this->getResourceDir('/mappings/tsantos')]);

        $this->createCacheDir('/hydrators', '/metadata');

        $this->serializer = (new SerializerBuilder())
            ->setMetadataDriver(new YamlDriver($fileLocator))
            ->setHydratorDir($this->getCacheDir('/hydrators'))
            ->setMetadataCacheDir($this->getCacheDir('/metadata'))
            ->enableBuiltInNormalizers()
            ->setDebug(false)
            ->build();
    }

    protected function doBenchSerialize(array $objects): void
    {
        $this->serializer->serialize($objects);
    }

    protected function doBenchDeserialize(string $content): void
    {
        $this->serializer->deserialize($content, Person::class . '[]');
    }

    public function getName(): string
    {
        return 'TSantos';
    }

    public function getPackageName(): string
    {
        return 'tsantos/serializer';
    }
}
