<?php
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

namespace TSantos\Benchmark;

abstract class SerializerBenchmarkSample extends BenchmarkSample
{
    abstract protected function serialize($object);

    public function run($iteration)
    {
        $person = new Person(
            $iteration,
            'Foo ',
            true,
            ['blue', 'red'],
            new Person($iteration, 'Foo\'s mother', false, ['blue', 'violet'])
        );

        return $this->serialize($person);
    }

    public function verify($result)
    {
        $object = json_decode($result, true);
        assert(is_int($object['id']), $this->getName());
        assert($object['name'] == 'Foo ', $this->getName());
        assert($object['married'] === true, $this->getName());
        assert($object['favoriteColors'] === ['blue', 'red'], $this->getName());
        assert(is_array($object['mother']), $this->getName());
        assert($object['mother']['id'] === $object['id'], $this->getName());
        assert($object['mother']['name'] === 'Foo\'s mother', $this->getName());
        assert($object['mother']['married'] === false, $this->getName());
        assert($object['mother']['favoriteColors'] === ['blue', 'violet'], $this->getName());
    }
}
