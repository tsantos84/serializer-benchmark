<?php

namespace TSantos\Benchmark\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TSantos\Benchmark\Unserialize\JmsSample;
use TSantos\Benchmark\Unserialize\SimpleSerializerSample;
use TSantos\Benchmark\Unserialize\SymfonyCustomDenormalizerSample;
use TSantos\Benchmark\Unserialize\SymfonySample;
use TSantos\Benchmark\Benchmark;

/**
 * Class DeserializeCommand
 *
 * @author Tales Santos <tales.augusto.santos@gmail.com>
 */
class DeserializeCommand extends Command
{
    /** @var  Benchmark */
    private $benchmark;

    protected function configure()
    {
        $this
            ->setName('deserialize')
            ->setDescription('Benchmarks the deserialization process')
            ->addOption('samples', 's', InputOption::VALUE_REQUIRED, 'Amount of samples the application will perform', 100)
            ->addOption('batch-count', 'b', InputOption::VALUE_REQUIRED, 'Quantity of objects per each deserialization', 1)
            ->addOption('exclude', 'e', InputOption::VALUE_IS_ARRAY|InputOption::VALUE_REQUIRED, 'Exclude a vendor from benchmark');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->benchmark = new Benchmark();

        $excludes = $input->getOption('exclude');

        if (!in_array('jms', $excludes)) {
            $this->benchmark->addSample(new JmsSample());
        }

        if (!in_array('symfony', $excludes)) {
            $this->benchmark->addSample(new SymfonySample());
        }

        if (!in_array('symfony-custom', $excludes)) {
            $this->benchmark->addSample(new SymfonyCustomDenormalizerSample());
        }

        if (!in_array('simple_serializer', $excludes)) {
            $this->benchmark->addSample(new SimpleSerializerSample());
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $samples = $input->getOption('samples');
        $batchCount = $input->getOption('batch-count');

        $style = new SymfonyStyle($input, $output);
        $style->title(sprintf('Performing <info>%d</info> deserialization samples, <info>%d</info> objects each', $samples, $batchCount));

        $result = $this->benchmark->run($samples, $batchCount);

        $style->table(['vendor', 'duration (ms)', 'relative to fastest, %'], $this->getHelper('result')->sort($result));
    }
}
