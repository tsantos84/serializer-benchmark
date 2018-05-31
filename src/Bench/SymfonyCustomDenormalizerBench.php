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

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use TSantos\Benchmark\AbstractBench;
use TSantos\Benchmark\Person;
use TSantos\Benchmark\Symfony\PersonDenormalizer;

class SymfonyCustomDenormalizerBench extends AbstractBench
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    public function bootstrap(): void
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new PersonDenormalizer(), new ArrayDenormalizer(), new ObjectNormalizer());
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    protected function doBenchSerialize(array $objects): void
    {
        $this->serializer->serialize($objects, 'json');
    }

    protected function doBenchDeserialize(string $content): void
    {
        $this->serializer->deserialize($content, Person::class.'[]', 'json');
    }

    public function getName(): string
    {
        return 'Symfony (custom normalizer)';
    }

    public function getPackageName(): string
    {
        return 'symfony/serializer';
    }
}
