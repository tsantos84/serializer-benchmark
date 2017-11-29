<?php

namespace TSantos\Benchmark\Console\Helper;

use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 * Class ResultHelper
 *
 * @author Tales Santos <tales.augusto.santos@gmail.com>
 */
class ResultHelper implements HelperInterface
{
    private $helperSet;

    public function setHelperSet(HelperSet $helperSet = null)
    {
        $this->helperSet = $helperSet;
    }

    public function getHelperSet()
    {
        return $this->helperSet;
    }

    public function getName()
    {
        return 'result';
    }

    public function sort(array $result): array
    {
        $rows = [];

        /** @var StopwatchEvent $event */
        foreach ($result as $vendor => $event) {
            $averageDuration = round($event->getDuration() / count($event->getPeriods()), 2);
            $rows[$vendor] = [
                'vendor' => $vendor,
                'duration' => $averageDuration,
            ];
        }

        usort($rows, function ($row1, $row2) {
            return $row2['duration'] < $row1['duration'];
        });

        return $rows;
    }
}
