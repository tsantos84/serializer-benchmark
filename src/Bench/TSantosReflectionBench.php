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

use TSantos\Benchmark\Person;
use TSantos\Serializer\SerializerBuilder;

/**
 * Class TSantosReflectionBench
 *
 * @author Tales Santos <tales.augusto.santos@gmail.com>
 */
class TSantosReflectionBench extends AbstractTSantosBench
{
    protected function configure(SerializerBuilder $builder): void
    {
        $builder->accessThroughReflection();
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
        return 'TSantos (reflection)';
    }
}
