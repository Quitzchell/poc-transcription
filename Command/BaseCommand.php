<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('Base command')
            ->setDescription('Base command for transcription of project.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Executing BaseCommand logic...');
        return Command::SUCCESS;
    }
}
