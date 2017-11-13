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
            $memory = sprintf('%.2F', $event->getMemory() / 1024 / 1024);
            $rows[$event->getDuration()] = [$vendor, $event->getDuration(), $memory];
        }

        asort($rows);

        return array_reverse($rows);
    }
}
