<?php

namespace TSantos\Benchmark;

/**
 * Interface BenchInterface
 * @package TSantos\Benchmark
 */
interface BenchInterface
{
    /**
     * Serialize a set of objects
     */
    public function benchSerialize(): void;

    /**
     * Deserialize a set of objects
     */
    public function benchDeserialize(): void;

    /**
     * Returns the vendor's name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the vendor's composer package name (e.g: tsantos/serializer)
     *
     * @return string
     */
    public function getPackageName(): string;
}
