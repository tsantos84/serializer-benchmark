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

use TSantos\Benchmark\AbstractBench;
use Zumba\JsonSerializer\JsonSerializer;

class ZumbaJsonSerializerBench extends AbstractBench
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
