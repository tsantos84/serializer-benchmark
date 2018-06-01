<?php

namespace TSantos\Benchmark\Symfony;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use TSantos\Benchmark\Person;

class PersonDenormalizer implements DenormalizerInterface, CacheableSupportsMethodInterface
{
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $person = new Person();

        if (array_key_exists('id', $data)) {
            $person->setId($data['id']);
        }
        if (array_key_exists('name', $data)) {
            $person->setName($data['name']);
        }
        if (array_key_exists('married', $data)) {
            $person->setMarried($data['married']);
        }
        if (array_key_exists('favoriteColors', $data)) {
            $person->setFavoriteColors($data['favoriteColors']);
        }
        if (isset($data['mother'])) {
            $person->setMother($this->denormalize($data['mother'], $class, $format, $context));
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
