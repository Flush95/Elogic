<?php

namespace Elogic\Import\Console;

use Magento\Framework\App\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Import extends Command
{
    private const PATH = 'path';

    /**
     * to run command please enter sudo php bin/magento shops:import --path="/your/path"
     */
    protected function configure()
    {
        $options = [
            new InputOption(
                self::PATH,
                null,
                InputOption::VALUE_REQUIRED,
                'Path'
            )
        ];

        $this->setName('shops:import')
            ->setDescription('Demo command line')
            ->setDefinition($options);

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return $this|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($path = $input->getOption(self::PATH)) {
            $objectManager = ObjectManager::getInstance();
            $import = $objectManager->create('Elogic\Import\Setup\InstallCsvData');
            $import->setCsvFilePath($path);
            $import->apply();

            $output->writeln("Path: " . $path);
        } else {
            $output->writeln("Path not found");
        }

        return $this;
    }
}
