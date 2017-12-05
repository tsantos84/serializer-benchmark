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

namespace TSantos\Benchmark\Serialize;

use Assert\Assertion;
use TSantos\Benchmark\BenchmarkSample;
use TSantos\Benchmark\Person;

abstract class SerializeBenchmarkSample extends BenchmarkSample
{
    abstract protected function serialize($object) : string;
    abstract protected function getSampleName() : string;

    public function run(?int $iteration = 0, ?int $batchCount = 1) : string
    {
        $persons = [];
        for ($i = 0; $i < $batchCount; $i++) {
            $persons[] = (new Person())
                ->setId($iteration)
                ->setName('Foo ')
                ->setMarried(true)
                ->setFavoriteColors(['blue', 'red'])
                ->setMother(
                    (new Person())
                        ->setId($iteration)
                        ->setName('Foo\'s mother')
                        ->setMarried(false)
                        ->setFavoriteColors(['blue', 'violet'])
                );
        }

        return $this->serialize($batchCount === 1 ? reset($persons) : $persons);
    }

    /**
     * @param $result
     * @throws \Assert\AssertionFailedException
     */
    public function verify($result)
    {
        $object = json_decode($result, true);
        Assertion::isArray($object);
        Assertion::eq($object['id'], 0);
        Assertion::eq($object['name'], 'Foo ');
        Assertion::true($object['married']);
        Assertion::eq($object['favoriteColors'], ['blue', 'red']);
        Assertion::isArray($object['mother']);
        Assertion::eq($object['mother']['id'], $object['id']);
        Assertion::eq($object['mother']['name'], 'Foo\'s mother');
        Assertion::false($object['mother']['married'], false);
        Assertion::eq($object['mother']['favoriteColors'], ['blue', 'violet']);
        Assertion::keyExists($object['mother'], 'mother');
    }

    final public function getName() : string
    {
        return $this->getSampleName();
    }
}
