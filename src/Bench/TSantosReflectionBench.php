<?php

namespace TSantos\Benchmark\Bench;

use TSantos\Benchmark\Person;
use TSantos\Serializer\SerializerBuilder;

/**
 * Class TSantosReflectionBench
 * @package TSantos\Benchmark\Bench
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
