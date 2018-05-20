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

use Metadata\Driver\FileLocator;
use Symfony\Component\Filesystem\Filesystem;
use TSantos\Serializer\Metadata\Driver\YamlDriver;
use TSantos\Serializer\SerializationContext;
use TSantos\Serializer\SerializerBuilder;
use TSantos\Serializer\TypeGuesser;

class TSantosSample extends SerializeBenchmarkSample
{
    protected $serializer;

    public function __construct()
    {
        $fileLocator = new FileLocator(['TSantos\Benchmark' => __DIR__ . '/../../mappings/tsantos']);

        $fs = new Filesystem();
        $fs->remove($path = __DIR__ . '/../../cache/tsantos');
        $fs->mkdir([$path . '/classes', $path . '/metadata']);

        $builder = (new SerializerBuilder())
            ->setMetadataDriver(new YamlDriver($fileLocator, new TypeGuesser()))
            ->setSerializerClassDir(__DIR__ . '/../../cache/tsantos/classes')
            ->setMetadataCacheDir(__DIR__ . '/../../cache/tsantos/metadata')
            ->enableBuiltInNormalizers()
            ->setDebug(false);

        if (false === $strategy = getenv('SERIALIZER_ACCESSOR_STRATEGY')) {
            $strategy = 'ACCESSORS';
        }

        if ('REFLECTION' === $strategy) {
            $builder->accessThroughReflection();
        }

        $this->serializer = $builder->build();
    }

    protected function serialize($object) : string
    {
        return $this->serializer->serialize($object, (new SerializationContext())->setSerializeNull(true));
    }

    public function getSampleName() : string
    {
        return 'tsantos';
    }
}
