<?php

namespace TSantos\Benchmark;

/**
 * Class BenchInterface
 * @package TSantos\Benchmark
 */
interface BenchInterface
{
    public function getName(): string;

    public function getPackageName(): string;
}
