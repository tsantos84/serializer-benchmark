<?php

namespace TSantos\Benchmark\Bench;

use TSantos\Benchmark\Person;

/**
 * Class TSantosAccessorBench
 * @package TSantos\Benchmark\Bench
 */
class TSantosAccessorBench extends AbstractTSantosBench
{
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
        return 'TSantos (accessors)';
    }
}
