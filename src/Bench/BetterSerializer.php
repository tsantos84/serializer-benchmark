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

use BetterSerializer\Builder;
use BetterSerializer\Common\SerializationType;
use BetterSerializer\Serializer;
use TSantos\Benchmark\AbstractBench;

/**
 * Class BetterSerializer
 *
 * @author Tales Santos <tales.augusto.santos@gmail.com>
 */
class BetterSerializer extends AbstractBench
{
    /**
     * @var Serializer
     */
    protected $serializer;

    public function bootstrap(): void
    {
        $this->createCacheDir('/better-serializer');
        $builder = new Builder();
        $builder->enableFilesystemCache($this->getCacheDir('/better-serializer'));
        $this->serializer = $builder->createSerializer();
    }

    protected function doBenchSerialize(array $objects): void
    {
        $this->serializer->serialize($objects, SerializationType::JSON());
    }

    protected function doBenchDeserialize(string $content, string $type): void
    {
        $this->serializer->deserialize($content, sprintf('array<%s>', $type), SerializationType::JSON());
    }

    public function getName(): string
    {
        return 'BetterSerializer';
    }

    public function getPackageName(): string
    {
        return 'better-serializer/better-serializer';
    }
}
