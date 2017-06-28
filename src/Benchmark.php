<?php

namespace TSantos\Benchmark;

use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class Benchmark
 *
 * @author Tales Santos <tales.augusto.santos@gmail.com>
 */
class Benchmark
{
    private $codes;

    public function addCode(string $vendor, callable $initializer, callable $code)
    {
        $this->codes[$vendor] = [
            'initializer' => $initializer,
            'code' => $code
        ];
    }

    public function run(int $interactions, array $vendors = null)
    {
        if (null === $vendors) {
            $vendors = array_keys($this->codes);
        }

        // assert class were auto loaded
        // warm up caches
        $this->_doRun(1, $vendors);

        // real run
        return $this->_doRun($interactions, $vendors);
    }

    private function _doRun(int $interactions, array $vendors)
    {
        $results = [];

        foreach ($vendors as $vendor) {
            $results[$vendor] = $this->_doRunVendor($vendor, $interactions);
        }

        return $results;
    }

    private function _doRunVendor(string $vendor, int $interactions)
    {
        $code = $this->codes[$vendor];

        $stopwatch = new Stopwatch();

        $result = $code['initializer']();
        $stopwatch->start('interaction');

        for ($i = 0; $i < $interactions; $i++) {
            $code['code']($result, $i);
            $stopwatch->lap('interaction');
        }
        $intEvent = $stopwatch->stop('interaction');

        return $intEvent;
    }
}
