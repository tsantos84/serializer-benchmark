<?php
/**
 * This file is part of the TSantos Serializer Bundle package.
 *
 * (c) Tales Santos <tales.augusto.santos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TSantos\Benchmark\Bench;

use Metadata\Driver\FileLocator;
use Symfony\Component\Filesystem\Filesystem;
use TSantos\Benchmark\AbstractBench;
use TSantos\Serializer\Metadata\Driver\YamlDriver;
use TSantos\Serializer\SerializerBuilder;
use TSantos\Serializer\SerializerInterface;
use TSantos\Serializer\TypeGuesser;

/**
 * Class AbstractTsantosBench
 *
 * @author Tales Santos <tales.augusto.santos@gmail.com>
 */
abstract class AbstractTSantosBench extends AbstractBench
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    public function init()
    {
        $fileLocator = new FileLocator(['TSantos\Benchmark' => __DIR__ . '/../Resources/mappings/tsantos']);

        $fs = new Filesystem();
        $fs->remove($path = '/tmp/serializer/cache/tsantos');
        $fs->mkdir([$path . '/classes', $path . '/metadata']);

        $builder = (new SerializerBuilder())
            ->setMetadataDriver(new YamlDriver($fileLocator, new TypeGuesser()))
            ->setSerializerClassDir('/tmp/serializer/cache/tsantos/classes')
            ->setMetadataCacheDir('/tmp/serializer/cache/tsantos/metadata')
            ->enableBuiltInNormalizers()
            ->setDebug(false);

        $this->configure($builder);

        $this->serializer = $builder->build();
    }

    abstract protected function configure(SerializerBuilder $builder);
}
