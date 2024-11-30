<?php

namespace App\Services\BlockParsing;

use App\Services\BlockParsing\DTO\BlockDTO;
use App\Services\FormFieldParsing\FormFieldTranscriptionService;

class BlockTranscriptionService
{
    private FileProcessor $fileProcessor;
    private XmlParser $xmlParser;
    private ClassGenerator $classGenerator;

    public function __construct(
        FileProcessor $fileProcessor,
        XmlParser $xmlParser,
        ClassGenerator $classGenerator,
    )
    {
        $this->fileProcessor = $fileProcessor;
        $this->xmlParser = $xmlParser;
        $this->classGenerator = $classGenerator;
    }

    public function process(): void
    {
        $this->fileProcessor->validateDirectory($this->fileProcessor->getInputDir());
        $xmlFiles = $this->fileProcessor->getXmlFiles();

        foreach ($xmlFiles as $file) {
            $xml = $this->xmlParser->parseFile($file);

            $formFieldTranscriptionService = new FormFieldTranscriptionService();
            $formFieldData = $formFieldTranscriptionService->process($xml);

            $blockDTO = new BlockDTO(
                blockName: $this->generateBlockName($file),
                blockTitle: $xml->attributes()->name,
                useStatements: $formFieldData->getUseStatements(),
                formSchema: $formFieldData->getFormSchema(),
                resolveSchema: $formFieldData->getResolveSchema(),
            );

            $classCode = $this->classGenerator->generate($blockDTO);

            $blockPathParts = array_map('ucwords', explode('\\', $blockDTO->getBlockName()));
            $directoryPath = $this->fileProcessor->getOutputDir() . "/app/Cms/Blocks/" . implode('/', array_slice($blockPathParts, 0, -1));
            $this->fileProcessor->ensureDirectoryExists($directoryPath);

            $filePath = $this->fileProcessor->getOutputDir() . "/app/Cms/Blocks/" . implode('/', $blockPathParts) . '.php';
            $this->classGenerator->writeClassFile($classCode, $filePath);
        }
    }

    private static function generateBlockName(string $filePath): string
    {
        $startPos = strpos($filePath, 'blocks/') + strlen('blocks/');
        $blockName = substr($filePath, $startPos);

        if (str_ends_with($blockName, '.xml')) {
            $blockName = substr($blockName, 0, -4);
        }

        return str_replace('/', '\\', $blockName);
    }
}
