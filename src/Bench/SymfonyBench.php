<?php

namespace TSantos\Benchmark\Bench;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\LoaderChain;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use TSantos\Benchmark\AbstractBench;

/**
 * Class SymfonyBench
 * @package TSantos\Benchmark\Bench
 */
class SymfonyBench extends AbstractBench
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function bootstrap(): void
    {
        $metadataFactory = new ClassMetadataFactory(new LoaderChain([
            new YamlFileLoader($this->getResourceDir('/mappings/symfony/Person.yaml')),
            new YamlFileLoader($this->getResourceDir('/mappings/symfony/Post.yaml')),
        ]));
        $encoders = array(new JsonEncoder());
        $normalizers = array(new PropertyNormalizer($metadataFactory), new ObjectNormalizer($metadataFactory), new ArrayDenormalizer());
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    protected function doBenchSerialize(array $objects): void
    {
        $this->serializer->serialize($objects, 'json');
    }

    protected function doBenchDeserialize(string $content, string $type): void
    {
        $this->serializer->deserialize($content, $type . '[]', 'json');
    }

    public function getName(): string
    {
        return 'Symfony';
    }

    public function getPackageName(): string
    {
        return 'symfony/serializer';
    }
}
