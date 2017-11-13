#!/usr/bin/php
<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use TSantos\Benchmark\Console\Command\DeserializeCommand;
use TSantos\Benchmark\Console\Command\SerializeCommand;
use TSantos\Benchmark\Console\Command\VendorsCommand;
use TSantos\Benchmark\Console\Helper\ResultHelper;

require __DIR__ . '/vendor/autoload.php';

$application = new Application('Serializer Benchmarker');
$application->setHelperSet(new HelperSet([
    new ResultHelper()
]));
$application->add(new SerializeCommand());
$application->add(new DeserializeCommand());
$application->add(new VendorsCommand());

$application->run();
