<?php

namespace TSantos\Benchmark\Bench;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\LoaderChain;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use TSantos\Benchmark\AbstractBench;
use TSantos\Benchmark\Symfony\PersonDenormalizer;

/**
 * Class SymfonyCustomDenormalizerBench
 * @package TSantos\Benchmark\Bench
 */
class SymfonyCustomDenormalizerBench extends AbstractBench
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    public function bootstrap(): void
    {
        $metadataFactory = new ClassMetadataFactory(new LoaderChain([
            new YamlFileLoader($this->getResourceDir('/mappings/symfony/Person.yaml')),
            new YamlFileLoader($this->getResourceDir('/mappings/symfony/Post.yaml')),
        ]));

        $encoders = array(new JsonEncoder());
        $normalizers = array(new PersonDenormalizer(), new ArrayDenormalizer(), new ObjectNormalizer($metadataFactory));
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    protected function doBenchSerialize(array $objects): void
    {
        $this->serializer->serialize($objects, 'json');
    }

    protected function doBenchDeserialize(string $content, string $type): void
    {
        $this->serializer->deserialize($content, $type.'[]', 'json');
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
