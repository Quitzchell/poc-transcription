<?php

require __DIR__ . '/vendor/autoload.php';

use App\Console\Command\TranscriptionCommand;
use App\Services\BlockParsing\BlockTranscriptionService;
use App\Services\BlockParsing\Utilities\ClassGenerator;
use App\Services\BlockParsing\Utilities\FileProcessor;
use Symfony\Component\Console\Application;

$fileProcessor = new FileProcessor(
    __DIR__ . '/input',  // Input directory
    __DIR__ . '/output'  // Output directory
);
$xmlParser = new \App\Services\BlockParsing\Utilities\XmlParser();
$classGenerator = new ClassGenerator();

$blockTranscriptionService = new BlockTranscriptionService(
    $fileProcessor,
    $xmlParser,
    $classGenerator
);

$command = new TranscriptionCommand($blockTranscriptionService);

$application = new Application('Transcription App', '1.0');
$application->add($command);
$application->run();