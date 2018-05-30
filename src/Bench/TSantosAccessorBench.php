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
use TSantos\Benchmark\Person;
use TSantos\Serializer\Metadata\Driver\YamlDriver;
use TSantos\Serializer\SerializerBuilder;
use TSantos\Serializer\SerializerInterface;
use TSantos\Serializer\TypeGuesser;

/**
 * Class SerializerBench
 *
 * @author Tales Santos <tales.augusto.santos@gmail.com>
 */
class TSantosAccessorBench extends AbstractTSantosBench
{
    protected function configure(SerializerBuilder $builder)
    {
    }

    protected function doBenchSerialize(array $objects)
    {
        $this->serializer->serialize($objects);
    }

    protected function doBenchDeserialize(string $content)
    {
        $this->serializer->deserialize($content, Person::class . '[]');
    }
}
