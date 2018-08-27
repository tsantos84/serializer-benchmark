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
    private $persons = [];

    /**
     * @var Post[]
     */
    private $posts = [];

    /**
     * @var string
     */
    private $personContent;

    /**
     * @var string
     */
    private $postContent;

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
        $posts = [];

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

        for ($i = 0; $i < 200; $i++) {
            $posts[] = new Post($i, 'Title ' . $i, 'Summary ' . $i);
        }

        $this->persons = $persons;
        $this->posts = $posts;
    }

    /**
     * Initialize the JSON content to be deserialized
     */
    final public function initContent(): void
    {
        $personContent = [];
        for ($i = 0; $i < 200; $i++) {
            $personContent[] = <<<JSON
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

        $postContent = [];
        for ($i = 0; $i < 200; $i++) {
            $postContent[] = <<<JSON
                {
                    "@type":"TSantos\\\Benchmark\\\Post",
                    "id":${i},
                    "title":"Foo ",
                    "summary":"Foo"
                }
JSON;
        }

        $this->personContent = sprintf('[%s]', implode(',', $personContent));
        $this->postContent = sprintf('[%s]', implode(',', $postContent));
    }

    /**
     * @Groups({"serialize"})
     */
    final public function benchSerialize(): void
    {
        $this->doBenchSerialize($this->persons);
    }

    /**
     * @Groups({"deserialize"})
     */
    final public function benchDeserialize(): void
    {
        $this->doBenchDeserialize($this->personContent, Person::class);
    }

    /**
     * @Groups({"serialize-reflection"})
     */
    final public function benchSerializeReflection(): void
    {
        $this->doBenchSerialize($this->posts);
    }

    /**
     * @Groups({"deserialize-reflection"})
     */
    final public function benchDeserializeReflection(): void
    {
        $this->doBenchDeserialize($this->postContent, Post::class);
    }

    /**
     * Return the resource directory
     *
     * @param string $suffix
     * @return string
     */
    final protected function getResourceDir(string $suffix = ''): string
    {
        return __DIR__ . '/Resources/' . ltrim($suffix, '/');
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
        return $this->cacheDir . DIRECTORY_SEPARATOR . ltrim($suffix, DIRECTORY_SEPARATOR);
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
     * @param string $type
     */
    abstract protected function doBenchDeserialize(string $content, string $type): void;
}
