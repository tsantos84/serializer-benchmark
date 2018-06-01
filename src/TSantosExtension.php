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

        $container->register('tsantos.blackfire_executor', function (Container $container) {
            return new BlackfireExecutor(
                $container->get('benchmark.remote.launcher'),
                $container->get('benchmark.executor.microtime')
            );
        }, ['benchmark_executor' => ['name' => 'blackfire']]);
    }

    public function getDefaultConfig()
    {
        return [];
    }
}
