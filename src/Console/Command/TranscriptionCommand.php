<?php

namespace App\Console\Command;

use App\Services\BlockParsing\BlockTranscriptionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TranscriptionCommand extends Command
{
    private BlockTranscriptionService $blockTranscriptionService;

    public function __construct(BlockTranscriptionService $blockTranscriptionService)
    {
        $this->blockTranscriptionService = $blockTranscriptionService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('app:transcription')
            ->setDescription('Processes transcription tasks');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->blockTranscriptionService->process();
        $output->writeln('Transcription completed.');
        return Command::SUCCESS;
    }
}

