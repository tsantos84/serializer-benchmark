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
            ['tsantos', '✓', '✕', $this->getVersion('tsantos/serializer')],
            ['symfony', '✓', '✓', $this->getVersion('symfony/serializer')],
            ['jms', '✓', '✓', $this->getVersion('jms/serializer')],
            ['simple serializer', '✓', '✓', $this->getVersion('opensoft/simple-serializer')],
            ['zumba json serializer', '✓', '✓', $this->getVersion('zumba/json-serializer')],
        ];

        $style->table(['name', 'serialization', 'deserialization', 'version'], $rows);
    }

    private function getVersion(string $package): string
    {
        $file = file_get_contents(__DIR__ . '/../../../composer.lock');
        $json = json_decode($file, true);

        foreach ($json['packages'] as $pck) {
            if ($pck['name'] === $package) {
                return $pck['version'];
            }
        }

        return 'N/A';
    }
}
