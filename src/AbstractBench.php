<?php

namespace TSantos\Benchmark;

use PhpBench\Benchmark\Metadata\Annotations\AfterMethods;
use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpBench\Benchmark\Metadata\Annotations\Groups;
use PhpBench\Benchmark\Metadata\Annotations\OutputTimeUnit;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class AbstractBench
 *
 * @author Tales Santos <tales.augusto.santos@gmail.com>
 * @BeforeMethods({"bootstrap", "initObjects", "initContent"})
 * @AfterMethods({"tearDown"})
 * @OutputTimeUnit("milliseconds", precision=3)
 */
abstract class AbstractBench implements BenchInterface
{
    /**
     * @var Person[]
     */
    private $objects = [];

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var Filesystem
     */
    private $fs;

    public function __construct()
    {
        $this->fs = new Filesystem();
        $this->cacheDir = '/tmp/serializer';
        $this->fs->mkdir($this->cacheDir);
    }

    /**
     * Initialize the set of objects to be serialized
     */
    final public function initObjects(): void
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

    /**
     * Initialize the JSON content to be deserialized
     */
    final public function initContent(): void
    {
        $content = [];
        for ($i = 0; $i < 200; $i++) {
            $content[] = <<<JSON
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

        $this->content = sprintf('[%s]', implode(',', $content));
    }

    /**
     * @Groups({"serialize"})
     */
    final public function benchSerialize(): void
    {
        $this->doBenchSerialize($this->objects);
    }

    /**
     * @Groups({"deserialize"})
     */
    final public function benchDeserialize(): void
    {
        $this->doBenchDeserialize($this->content);
    }

    /**
     * Return the resource directory
     *
     * @param string $suffix
     * @return string
     */
    final protected function getResourceDir(string $suffix = ''): string
    {
        return __DIR__ . '/Resources/' . $suffix;
    }

    /**
     * Clean up the benchmark
     */
    public function tearDown(): void
    {
        $this->fs->remove($this->cacheDir);
    }

    /**
     * Creates the given directories inside the cache dir
     *
     * @param string ...$dirs
     */
    protected function createCacheDir(string ...$dirs): void
    {
        array_walk($dirs, function (string &$dir) {
            $dir = $this->cacheDir . DIRECTORY_SEPARATOR . ltrim($dir, DIRECTORY_SEPARATOR);
        });

        $this->fs->mkdir($dirs);
    }

    /**
     * Returns the cache dir.
     *
     * Optionally you can pass a suffix which will append to the cache directory.
     *
     * @param string $suffix
     * @return string
     */
    protected function getCacheDir(string $suffix = ''): string
    {
        return $this->cacheDir . $suffix;
    }

    /**
     * Bootstraps the vendor's services
     */
    abstract public function bootstrap(): void;

    /**
     * Performs the serialize benchmark
     *
     * @param array $objects
     */
    abstract protected function doBenchSerialize(array $objects): void;

    /**
     * Performs the deserialize benchmark
     *
     * @param string $content
     */
    abstract protected function doBenchDeserialize(string $content): void;
}
