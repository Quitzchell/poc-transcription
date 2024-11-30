<?php

namespace Command;

use Actions\Blocks\TranscribeBlocksAction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class TranscribeAction extends BaseCommand
{
    private TranscribeBlocksAction $transcribeBlocksAction;


    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->transcribeBlocksAction = new TranscribeBlocksAction();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:parse-xml')
            ->setDescription('Parses an XML file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesystem = new Filesystem();
        $inputDir = self::INPUT_DIR;
        $outputDir = self::OUTPUT_DIR;

        if (!file_exists($inputDir)) {
            $output->writeln('<error>Input directory does not exist.</error>');
            return Command::FAILURE;
        }

        if (!$filesystem->exists($outputDir)) {
            $filesystem->mkdir($outputDir);
        }

        $this->transcribeBlocksAction->execute();

        return Command::SUCCESS;
    }
}
