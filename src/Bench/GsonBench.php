<?php

namespace TSantos\Benchmark\Bench;

use Tebru\Gson\Gson;
use Tebru\Gson\PropertyNamingPolicy;
use TSantos\Benchmark\AbstractBench;
use TSantos\Benchmark\Person;

/**
 * Class GsonBench
 * @package TSantos\Benchmark\Bench
 */
class GsonBench extends AbstractBench
{
    /**
     * @var Gson
     */
    protected $gson;

    public function bootstrap(): void
    {
        $this->gson = Gson::builder()
            ->enableCache(true)
            ->setCacheDir($this->getCacheDir())
            ->setPropertyNamingPolicy(PropertyNamingPolicy::IDENTITY)
            ->build();
    }

    protected function doBenchSerialize(array $objects): void
    {
        $this->gson->toJson($objects);
    }

    protected function doBenchDeserialize(string $content): void
    {
        $this->gson->fromJson($content, 'array<' . Person::class . '>');
    }

    public function getName(): string
    {
        return 'Gson';
    }

    public function getPackageName(): string
    {
        return 'tebru/gson-php';
    }
}
