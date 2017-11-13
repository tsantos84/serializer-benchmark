<?php

namespace TSantos\Benchmark\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class VendorsCommand
 *
 * @author Tales Santos <tales.augusto.santos@gmail.com>
 */
class VendorsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('vendors')
            ->setDescription('List all vendors available in this application');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);

        $rows = [
            ['tsantos', '✓', '✕'],
            ['symfony', '✓', '✓'],
            ['jms', '✓', '✓'],
            ['simple serializer', '✓', '✓']
        ];

        $style->table(['name', 'serialization', 'deserialization'], $rows);
    }
}
