<?php

namespace App\Services\BlockParsing\Utilities;

class FileProcessor
{
    private string $inputDir;
    private string $outputDir;

    public function __construct(string $inputDir, string $outputDir)
    {
        $this->inputDir = $inputDir;
        $this->outputDir = $outputDir;
    }

    public function getInputDir(): string
    {
        return $this->inputDir;
    }

    public function getOutputDir(): string
    {
        return $this->outputDir;
    }

    public function validateDirectory(string $path): bool
    {
        if (!is_dir($path)) {
            throw new \InvalidArgumentException("The directory '$path' does not exist.");
        }
        if (!is_readable($path)) {
            throw new \InvalidArgumentException("The directory '$path' is not readable.");
        }

        return true;
    }

    public function getXmlFiles(): array
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->inputDir)
        );

        $xmlFiles = [];
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'xml') {
                $xmlFiles[] = $file->getPathname();
            }
        }

        return $xmlFiles;
    }

    public function ensureDirectoryExists(string $path): void
    {
        if (!is_dir($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
            throw new \RuntimeException("Failed to create directory: {$path}");
        }
    }
}
