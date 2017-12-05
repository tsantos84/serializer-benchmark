<?php
declare(strict_types=1);
/**
 * Copyright (C) 2017 Eduard Sukharev
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace TSantos\Benchmark\Unserialize;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use TSantos\Benchmark\Person;

class SymfonySample extends UnserializeBenchmarkSample
{
    protected $serializer;

    public function __construct()
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer(), new ArrayDenormalizer());
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    protected function unserialize(string $json)
    {
        return $this->serializer->deserialize($json, Person::class.'[]', 'json');
    }

    public function getSampleName() : string
    {
        return 'symfony';
    }

    /**
     * Symfony serializer creates an object initialized with nulls if it was given null,
     * thus `parent` field is not null here
     *
     * @param $objects
     */
    public function verify($objects)
    {
        $object = reset($objects);
        assert(get_class($object) === Person::class, $this->getName());
        /** @var Person $object */
        assert($object->getId() === 0, $this->getName());
        assert($object->getName() === 'Foo ', $this->getName());
        assert($object->isMarried() === true, $this->getName());
        assert($object->getFavoriteColors() === ['blue', 'red'], $this->getName());
        assert(is_object($mother = $object->getMother()), $this->getName());
        assert($mother->getId() === $object->getId(), $this->getName());
        assert($mother->getName() === 'Foo\'s mother', $this->getName());
        assert($mother->getMarried() === false, $this->getName());
        assert($mother->getFavoriteColors() === ['blue', 'violet'], $this->getName());

        assert($mother->getMother() instanceof Person, $this->getName());
    }
}
