<?php

namespace TSantos\Benchmark\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TSantos\Benchmark\Serialize\JmsSample;
use TSantos\Benchmark\Serialize\SimpleSerializerSample;
use TSantos\Benchmark\Serialize\SymfonySample;
use TSantos\Benchmark\Serialize\TsantosSample;
use TSantos\Benchmark\Benchmark;

/**
 * Class SerializeCommand
 *
 * @author Tales Santos <tales.augusto.santos@gmail.com>
 */
class SerializeCommand extends Command
{
    /** @var  Benchmark */
    private $benchmark;

    protected function configure()
    {
        $this
            ->setName('serialize')
            ->setDescription('Benchmarks the serialization process')
            ->addOption('interactions', 'i', InputOption::VALUE_REQUIRED, 'Amount of serializations each vendor will perform', 100)
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

        if (!in_array('tsantos', $excludes)) {
            $this->benchmark->addSample(new TsantosSample());
        }

        if (!in_array('simple_serializer', $excludes)) {
            $this->benchmark->addSample(new SimpleSerializerSample());
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $interactions = $input->getOption('interactions');

        $style = new SymfonyStyle($input, $output);
        $style->title(sprintf('Performing <info>%d</info> serialization interactions', $interactions));

        $result = $this->benchmark->run($interactions);

        $style->table(['vendor', 'duration (ms)', 'memory (MiB)'], $this->getHelper('result')->sort($result));
    }
}
