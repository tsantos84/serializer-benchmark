<?php

namespace TSantos\Benchmark\Bench;

use NilPortugues\Serializer\JsonSerializer;
use TSantos\Benchmark\AbstractBench;

/**
 * Class NilPortuguesBench
 * @package TSantos\Benchmark\Bench
 */
class NilPortuguesBench extends AbstractBench
{
    /**
     * @var JsonSerializer
     */
    private $serializer;

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
        return 'Nil Portugues';
    }

    public function getPackageName(): string
    {
        return 'nilportugues/serializer';
    }
}
