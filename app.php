<?php

use TSantos\Benchmark\Benchmark;
use TSantos\Benchmark\Serialize\JmsSample;
use TSantos\Benchmark\Serialize\SimpleSerializerSample;
use TSantos\Benchmark\Serialize\SymfonySample;
use TSantos\Benchmark\Serialize\TsantosSample;

require __DIR__ . '/vendor/autoload.php';

$benchmark = new Benchmark();

$benchmark->addSample(new SymfonySample());
$benchmark->addSample(new TsantosSample());
$benchmark->addSample(new SimpleSerializerSample());
$benchmark->addSample(new JmsSample());

$interactions = (int) ($argv[1] ?? 10);

if ($interactions === 0) {
    $interactions = 10;
}

echo "\nSerializing $interactions objects on " . join(', ', $benchmark->listSampleNames()) . "\n\n";

$result = $benchmark->run($interactions);

foreach ($result as $vendor => $event) {
    echo sprintf("%s: %s\n", str_pad($vendor, 7, ' ', STR_PAD_RIGHT), $event);
}

echo "\n";
