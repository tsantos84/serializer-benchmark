<?php

use TSantos\Benchmark\Benchmark;
use TSantos\Benchmark\Serialize\JmsSample as SerializeJms;
use TSantos\Benchmark\Serialize\SimpleSerializerSample as SerializeSimple;
use TSantos\Benchmark\Serialize\SymfonySample as SerializeSymfony;
use TSantos\Benchmark\Serialize\TsantosSample as SerializeTsantos;

require __DIR__ . '/vendor/autoload.php';

$benchmark = new Benchmark();

$benchmark->addSample(new SerializeJms());
$benchmark->addSample(new SerializeSymfony());
$benchmark->addSample(new SerializeSimple());
$benchmark->addSample(new SerializeTsantos());

$interactions = (int) ($argv[1] ?? 10);

if ($interactions === 0) {
    $interactions = 10;
}

echo "\nBenchmarking $interactions iterations with " . join(', ', $benchmark->listSampleNames()) . "\n\n";

$result = $benchmark->run($interactions);

foreach ($result as $vendor => $event) {
    echo sprintf("%s: %s\n", str_pad($vendor, 7, ' ', STR_PAD_RIGHT), $event);
}

echo "\n";
