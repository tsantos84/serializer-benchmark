<?php

namespace TSantos\Benchmark\Bench;

use TSantos\Benchmark\AbstractBench;
use Zumba\JsonSerializer\JsonSerializer;

/**
 * Class ZumbaJsonSerializerBench
 * @package TSantos\Benchmark\Bench
 */
class ZumbaJsonBench extends AbstractBench
{
    /**
     * @var JsonSerializer
     */
    protected $serializer;

    public function bootstrap(): void
    {
        $this->serializer = new JsonSerializer();
    }

    protected function doBenchSerialize(array $objects): void
    {
        $this->serializer->serialize($objects);
    }

    protected function doBenchDeserialize(string $content): void
    {
        $this->serializer->unserialize($content);
    }

    public function getName(): string
    {
        return 'Zumba';
    }

    public function getPackageName(): string
    {
        return 'zumba/json-serializer';
    }
}
