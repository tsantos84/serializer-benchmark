<?php

namespace TSantos\Benchmark;

use PhpBench\DependencyInjection\Container;
use PhpBench\DependencyInjection\ExtensionInterface;
use TSantos\Benchmark\Command\VendorCommand;

/**
 * Class Extension
 * @package TSantos\Benchmark
 */
class TSantosExtension implements ExtensionInterface
{
    public function load(Container $container)
    {
        $container->register('tsantos.vendor_command', function (Container $container) {
            return new VendorCommand(
                $container->get('benchmark.benchmark_finder'),
                $container->getParameter('path')
            );
        }, ['console.command' => []]);
    }

    public function getDefaultConfig()
    {
        return [];
    }
}
