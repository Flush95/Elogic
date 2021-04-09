<?php

namespace Elogic\StoreLocator\Console;

use Elogic\StoreLocator\Helper\InstallCsvData;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Import extends Command
{
    private const PATH = 'path';
    /**
     * @var InstallCsvData
     */
    private $installCsvData;

    /**
     * Import constructor.
     * @param string|null $name
     * @param InstallCsvData $installCsvData
     */
    public function __construct(InstallCsvData $installCsvData, string $name = null)
    {
        parent::__construct($name);
        $this->installCsvData = $installCsvData;
    }

    /**
     * to run this command please enter sudo php bin/magento shops:import --path="/your/path"
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
            ->setDescription(__('Demo command line'))
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
            $import = $this->installCsvData;
            $import->setCsvFilePath($path);
            $import->apply();

            $output->writeln(__("Path: " . $path));
        } else {
            $output->writeln(__("Path not found"));
        }

        return $this;
    }
}
