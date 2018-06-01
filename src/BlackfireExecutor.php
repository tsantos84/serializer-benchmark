<?php

namespace TSantos\Benchmark;

use PhpBench\Benchmark\Executor\BaseExecutor;
use PhpBench\Benchmark\Executor\MicrotimeExecutor;
use PhpBench\Benchmark\Metadata\SubjectMetadata;
use PhpBench\Benchmark\Remote\Launcher;
use PhpBench\Benchmark\Remote\Payload;
use PhpBench\Model\Iteration;
use PhpBench\Registry\Config;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BlackfireExecutor
 * @package TSantos\Benchmark
 */
class BlackfireExecutor extends BaseExecutor
{
    private $launcher;

    /**
     * @var MicrotimeExecutor
     */
    private $microtimeExecutor;

    public function __construct(Launcher $launcher, MicrotimeExecutor $microtimeExecutor)
    {
        parent::__construct($launcher);
        $this->launcher = $launcher;
        $this->microtimeExecutor = $microtimeExecutor;
    }

    public function execute(SubjectMetadata $subjectMetadata, Iteration $iteration, Config $config)
    {
        $tokens = [
            'class' => $subjectMetadata->getBenchmark()->getClass(),
            'file' => $subjectMetadata->getBenchmark()->getPath(),
            'subject' => $subjectMetadata->getName(),
            'revolutions' => $iteration->getVariant()->getRevolutions(),
            'beforeMethods' => var_export($subjectMetadata->getBeforeMethods(), true),
            'afterMethods' => var_export($subjectMetadata->getAfterMethods(), true),
            'parameters' => var_export($iteration->getVariant()->getParameterSet()->getArrayCopy(), true),
            'warmup' => $iteration->getVariant()->getWarmup() ?: 0,
            'profile' => $config['profile'] ? 'true' : 'false'
        ];
        $payload = $this->launcher->payload(__DIR__ . '/Resources/blackfire.template', $tokens);

        return $this->launch($payload, $iteration, $config);
    }

    protected function launch(Payload $payload, Iteration $iteration, Config $config)
    {
        return $this->microtimeExecutor->launch($payload, $iteration, $config);
    }

    public function configure(OptionsResolver $options)
    {
        $options->setDefaults([
            'profile' => true
        ]);
    }
}
