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

use Assert\Assertion;
use TSantos\Benchmark\BenchmarkSample;
use TSantos\Benchmark\Person;

abstract class UnserializeBenchmarkSample extends BenchmarkSample
{
    abstract protected function unserialize(string $json);
    abstract protected function getSampleName() : string;

    public function run(?int $iteration = 0, ?int $batchCount = 1)
    {
        $jsons = [];
        for ($i = 0; $i < $batchCount; $i++) {
            $jsons[] = <<<JSON
                {
                    "@type":"TSantos\\\Benchmark\\\Person",
                    "id":${iteration},
                    "name":"Foo ",
                    "mother":{
                        "@type":"TSantos\\\Benchmark\\\Person",
                        "id":${iteration},
                        "name":"Foo's mother",
                        "mother":null,
                        "married":false,
                        "favoriteColors":["blue","violet"]
                    },
                    "married":true,
                    "favoriteColors":["blue","red"]
                }
JSON;
        }
        $json = sprintf('[%s]', implode(',', $jsons));

        return $this->unserialize($json);
    }

    /**
     * @param $objects
     * @throws \Assert\AssertionFailedException
     */
    public function verify($objects)
    {
        /** @var Person $object */
        $object = reset($objects);
        Assertion::isInstanceOf($object, Person::class, $this->getName() . ': object expected to be of Person class');
        Assertion::eq($object->getId(), 0, $this->getName() . ': object id mismatch');
        Assertion::eq($object->getName(), 'Foo ', $this->getName() . ': object name mismatch');
        Assertion::true($object->isMarried(), $this->getName() . ': object marriage status mismatch');
        Assertion::eq($object->getFavoriteColors(), ['blue', 'red'], $this->getName() . ': object favorite colors mismatch');
        Assertion::isInstanceOf($mother = $object->getMother(), Person::class, $this->getName() . ': object\'s parent expected to be of Person class');
        Assertion::eq($mother->getId(), $object->getId(), $this->getName() . ': object\'s parent id mismatch');
        Assertion::eq($mother->getName(), 'Foo\'s mother', $this->getName() . ': object\'s parent name mismatch');
        Assertion::false($mother->getMarried(), false, $this->getName() . ': object\'s parent marriage status mismatch');
        Assertion::eq($mother->getFavoriteColors(), ['blue', 'violet'], $this->getName() . ': object\'s parent favorite colors mismatch');
        Assertion::null($mother->getMother(), $this->getName() . ': property expected to be null');
    }

    final public function getName() : string
    {
        return $this->getSampleName();
    }
}
