<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseXmlCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('Parses an XML file.')
            ->addArgument('xmlFile', InputArgument::REQUIRED, 'Path to the XML file to parse.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $xmlFile = $input->getArgument('xmlFile');

        if (!file_exists($xmlFile)) {
            $output->writeln('<error>XML file not found.</error>');
            return Command::FAILURE;
        }

        $xmlContent = simplexml_load_file($xmlFile);

        if (!$xmlContent) {
            $output->writeln('<error>XML file not found.</error>');
        }



        $output->writeln('Executing BaseCommand logic...');
        return Command::SUCCESS;
    }
}
