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

    /**
     * @var Filesystem
     */
    protected $fs;

    public function bootstrap(): void
    {
        $fileLocator = new FileLocator(['TSantos\Benchmark' => __DIR__ . '/../Resources/mappings/tsantos']);

        $this->fs = new Filesystem();
        $this->fs->mkdir(['/tmp/tsantos/classes', '/tmp/tsantos/metadata']);

        $builder = (new SerializerBuilder())
            ->setMetadataDriver(new YamlDriver($fileLocator, new TypeGuesser()))
            ->setSerializerClassDir('/tmp/tsantos/classes')
            ->setMetadataCacheDir('/tmp/tsantos/metadata')
            ->enableBuiltInNormalizers()
            ->setDebug(false);

        $this->configure($builder);

        $this->serializer = $builder->build();
    }

    public function clear(): void
    {
        $this->fs->remove('/tmp/tsantos');
    }

    abstract protected function configure(SerializerBuilder $builder): void;

    public function getPackageName(): string
    {
        return 'tsantos/serializer';
    }
}
