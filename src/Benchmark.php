<?php
declare(strict_types=1);

namespace TSantos\Benchmark;

use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 * Class Benchmark
 *
 * @author Tales Santos <tales.augusto.santos@gmail.com>
 */
class Benchmark
{
    /** @var BenchmarkSample[] */
    private $samples;

    /** @var StopwatchEvent[] */
    private $results;

    public function addSample(BenchmarkSample $sample)
    {
        $this->samples[] = $sample;
    }

    public function listSampleNames() : array
    {
        return array_map(function(BenchmarkSample $sample) {
            return $sample->getName();
        }, $this->samples);
    }

    public function run(int $interactions) : array
    {
        $this->warmup();
        $this->doRun($interactions);

        return $this->results;
    }

    private function doRun(int $interactions)
    {
        foreach ($this->samples as $sample) {
            $stopwatch = new Stopwatch();

            $stopwatch->start('interaction');
            for ($i = 0; $i < $interactions; $i++) {
                $sample->run($i);
                $stopwatch->lap('interaction');
            }
            $intEvent = $stopwatch->stop('interaction');

            $this->results[$sample->getName()] = $intEvent;
        }
    }

    private function warmup()
    {
        foreach ($this->samples as $sample) {
            $result = $sample->run();
            $sample->verify($result);
        }
    }
}
