<?php

use JMS\Serializer\Serializer as JMSSerializer;
use JMS\Serializer\SerializerBuilder as JMSSerializerBuilder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SFSerializer;
use TSantos\Benchmark\Benchmark;
use TSantos\Benchmark\Person;
use TSantos\Serializer\Metadata\Driver\ArrayDriver;
use TSantos\Serializer\Serializer;
use TSantos\Serializer\SerializerBuilder;

require __DIR__ . '/vendor/autoload.php';

$benchmark = new Benchmark();

$benchmark->addCode('symfony', function () {
    $encoders = array(new JsonEncoder());
    $normalizers = array(new ObjectNormalizer());
    $serializer = new SFSerializer($normalizers, $encoders);
    return $serializer;
}, function (SFSerializer $serializer, int $interaction) {
    $person = new Person(
        $interaction,
        'Foo ',
        true,
        new Person($interaction, 'Foo\'s mother', false)
    );
    $serializer->serialize($person, 'json');
});

$benchmark->addCode('tsantos', function () {
    $serializer = (new SerializerBuilder())
        ->setMetadataDriver(new ArrayDriver([
            Person::class => [
                'properties' => [
                    'id' => [],
                    'name' => [],
                    'married' => [
                        'getter' => 'isMarried'
                    ],
                    'mother' => [
                        'type' => Person::class
                    ]
                ]
            ]
        ]))
        ->setCacheDir(__DIR__ . '/cache/tsantos')
        ->setDebug(false)
        ->build();
    return $serializer;
}, function (Serializer $serializer, int $interaction) {
    $person = new Person(
        $interaction,
        'Foo ',
        true,
        new Person($interaction, 'Foo\'s mother', false)
    );
    $serializer->serialize($person, 'json');
});

$benchmark->addCode('jms', function () {
    return JMSSerializerBuilder::create()
        ->setDebug(false)
        ->setCacheDir(__DIR__ . '/cache/jms')
        ->addMetadataDir(__DIR__ . '/mappings/jms', 'Benchmark\\Benchmark')
        ->build();
}, function (JMSSerializer $serializer, int $interaction) {
    $person = new Person(
        $interaction,
        'Foo ',
        true,
        new Person($interaction, 'Foo\'s mother', false)
    );
    $serializer->serialize($person, 'json');
});

$interactions = (int) ($argv[1] ?? 10);

if ($interactions === 0) {
    $interactions = 10;
}
$vendors = ['jms', 'symfony', 'tsantos'];

echo "\nSerializing $interactions objects on " . join(', ', $vendors) . "\n\n";

$result = $benchmark->run($interactions, $vendors);

foreach ($result as $vendor => $event) {
    echo sprintf("%s: %s\n", str_pad($vendor, 7, ' ', STR_PAD_RIGHT), $event);
}

echo "\n";
