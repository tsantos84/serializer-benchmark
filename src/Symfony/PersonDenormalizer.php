<?php

namespace TSantos\Benchmark\Symfony;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use TSantos\Benchmark\Person;

class PersonDenormalizer implements DenormalizerInterface, SerializerAwareInterface, CacheableSupportsMethodInterface
{
    use SerializerAwareTrait;

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $person = new Person();

        if (isset($data['id']) || \array_key_exists('id', $data)) {
            if (null !== $value = $data['id']) {
                $person->setId($data['id']);
            } else {
                $person->setId(null);
            }
        }
        if (isset($data['name']) || \array_key_exists('name', $data)) {
            if (null !== $value = $data['name']) {
                $person->setName($data['name']);
            } else {
                $person->setName(null);
            }
        }
        if (isset($data['married']) || \array_key_exists('married', $data)) {
            if (null !== $value = $data['married']) {
                $person->setMarried($data['married']);
            } else {
                $person->setMarried(null);
            }
        }
        if (isset($data['favoriteColors']) || \array_key_exists('favoriteColors', $data)) {
            if (null !== $value = $data['favoriteColors']) {
                foreach ($data['favoriteColors'] as $key => $color) {
                    $value[$key] = (string) $color;
                }
                $person->setFavoriteColors($value);
            } else {
                $person->setFavoriteColors(null);
            }
        }
        if (isset($data['mother']) || \array_key_exists('mother', $data)) {
            if (null !== $value = $data['mother']) {
                $person->setMother($this->serializer->denormalize($value, $class, $format, $context));
            } else {
                $person->setMother(null);
            }
        }

        return $person;
    }

    /**
     * Checks whether the given class is supported for denormalization by this normalizer.
     *
     * @param mixed $data Data to denormalize from
     * @param string $type The class to which the data should be denormalized
     * @param string $format The format being deserialized from
     *
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Person::class;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
