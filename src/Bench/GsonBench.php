<?php

namespace TSantos\Benchmark\Bench;

use Symfony\Component\Cache\Simple\ApcuCache;
use Tebru\Gson\Gson;
use Tebru\Gson\PropertyNamingPolicy;
use TSantos\Benchmark\AbstractBench;

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
            ->setCache(new ApcuCache('GsonMetadata'))
            ->setPropertyNamingPolicy(PropertyNamingPolicy::IDENTITY)
            ->build();
    }

    protected function doBenchSerialize(array $objects): void
    {
        $this->gson->toJson($objects);
    }

    protected function doBenchDeserialize(string $content, string $type): void
    {
        $this->gson->fromJson($content, 'array<' . $type . '>');
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
