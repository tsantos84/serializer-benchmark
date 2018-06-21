<?php

namespace TSantos\Benchmark\Bench;

use Metadata\Driver\FileLocator;
use Symfony\Component\Filesystem\Filesystem;
use TSantos\Benchmark\AbstractBench;
use TSantos\Serializer\Metadata\Driver\YamlDriver;
use TSantos\Serializer\SerializerBuilder;
use TSantos\Serializer\SerializerInterface;

/**
 * Class AbstractTSantosBench
 * @package TSantos\Benchmark\Bench
 */
abstract class AbstractTSantosBench extends AbstractBench
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

        $builder = (new SerializerBuilder())
            ->setMetadataDriver(new YamlDriver($fileLocator))
            ->setHydratorDir($this->getCacheDir('/hydrators'))
            ->setMetadataCacheDir($this->getCacheDir('/metadata'))
            ->enableBuiltInNormalizers()
            ->setDebug(false);

        $this->configure($builder);

        $this->serializer = $builder->build();
    }

    public function getPackageName(): string
    {
        return 'tsantos/serializer';
    }

    protected function configure(SerializerBuilder $builder): void
    {
    }
}
