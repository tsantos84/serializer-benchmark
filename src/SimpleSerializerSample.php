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

use Opensoft\SimpleSerializer\Adapter\ArrayAdapter;
use Opensoft\SimpleSerializer\Adapter\JsonAdapter;
use Opensoft\SimpleSerializer\Metadata\Driver\FileLocator;
use Opensoft\SimpleSerializer\Metadata\Driver\YamlDriver;
use Opensoft\SimpleSerializer\Metadata\MetadataFactory;
use Opensoft\SimpleSerializer\Serializer;

class SimpleSerializerSample extends SerializerBenchmarkSample
{
    public function __construct()
    {
        $yamlDriver = new YamlDriver(new FileLocator([__DIR__ . '/mappings/simple-serializer']));
        $metadataFactory = new MetadataFactory($yamlDriver);
        $jsonAdapter = new JsonAdapter();
        $arrayAdapter = new ArrayAdapter($metadataFactory);

        $this->serializer = new Serializer($arrayAdapter, $jsonAdapter);
    }

    public function getName()
    {
        return 'simple serializer';
    }
}