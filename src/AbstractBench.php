<?php

namespace TSantos\Benchmark;

use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpBench\Benchmark\Metadata\Annotations\Groups;
use PhpBench\Benchmark\Metadata\Annotations\OutputTimeUnit;

/**
 * Class AbstractBench
 *
 * @author Tales Santos <tales.augusto.santos@gmail.com>
 * @BeforeMethods({"init", "initObjects", "initContent"})
 * @OutputTimeUnit("milliseconds", precision=3)
 */
abstract class AbstractBench
{
    /**
     * @var Person[]
     */
    private $objects = [];

    /**
     * @var string
     */
    private $content;

    final public function initObjects()
    {
        $persons = [];
        for ($i = 0; $i < 200; $i++) {
            $persons[] = (new Person())
                ->setId($i)
                ->setName('Foo ')
                ->setMarried(true)
                ->setFavoriteColors(['blue', 'red'])
                ->setMother(
                    (new Person())
                        ->setId($i+1)
                        ->setName('Foo\'s mother')
                        ->setMarried(false)
                        ->setFavoriteColors(['blue', 'violet'])
                );
        }

        $this->objects = $persons;
    }

    final public function initContent()
    {
        $jsons = [];
        for ($i = 0; $i < 200; $i++) {
            $jsons[] = <<<JSON
                {
                    "@type":"TSantos\\\Benchmark\\\Person",
                    "id":${i},
                    "name":"Foo ",
                    "mother":{
                        "@type":"TSantos\\\Benchmark\\\Person",
                        "id":${i},
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

        $content = sprintf('[%s]', implode(',', $jsons));

        return $this->content = $content;
    }

    /**
     * @Groups({"serialize"})
     */
    final public function benchSerialize()
    {
        $this->doBenchSerialize($this->objects);
    }

    /**
     * @Groups({"deserialize"})
     */
    final public function benchDeserialize()
    {
        $this->doBenchDeserialize($this->content);
    }

    abstract public function init();

    abstract protected function doBenchSerialize(array $objects);

    abstract protected function doBenchDeserialize(string $content);
}
