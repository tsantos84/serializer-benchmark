<?php

namespace TSantos\Benchmark\Command;

use PhpBench\Benchmark\BenchmarkFinder;
use PhpBench\Benchmark\Metadata\BenchmarkMetadata;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TSantos\Benchmark\BenchInterface;

/**
 * Class VendorCommand
 * @package TSantos\Benchmark\Command
 */
class VendorCommand extends Command
{
    /**
     * @var BenchmarkFinder
     */
    private $finder;
    /**
     * @var string
     */
    private $path;

    /**
     * VendorCommand constructor.
     * @param BenchmarkFinder $finder
     * @param string $path
     */
    public function __construct(BenchmarkFinder $finder, string $path)
    {
        parent::__construct();
        $this->finder = $finder;
        $this->path = $path;
    }

    protected function configure()
    {
        $this
            ->setName('vendors')
            ->setDescription('List all serializer vendors in this benchmark');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $benchmarks = $this->finder->findBenchmarks($this->path);

        $rows = [];

        /** @var BenchmarkMetadata $benchmark */
        foreach ($benchmarks as $benchmark) {

            $ref = new \ReflectionClass($benchmark->getClass());

            if (!$ref->implementsInterface(BenchInterface::class)) {
                continue;
            }

            $instance = $ref->newInstanceWithoutConstructor();

            $rows[] = [$instance->getName(), $this->getVersion($instance->getPackageName())];
        }

        $style = new SymfonyStyle($input, $output);
        $style->table(['name', 'version'], $rows);
    }
    private function getVersion(string $package): string
    {
        $file = file_get_contents(__DIR__ . '/../../composer.lock');
        $json = json_decode($file, true);
        foreach ($json['packages'] as $pck) {
            if ($pck['name'] === $package) {
                return $pck['version'];
            }
        }
        return 'N/A';
    }
}
