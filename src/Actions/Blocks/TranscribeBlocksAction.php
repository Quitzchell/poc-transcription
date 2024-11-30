<?php

namespace Actions\Blocks;

use Actions\FormFields\TranscribeRichTextFormFieldAction;
use Actions\FormFields\TranscribeTextFormFieldAction;
use Enums\FormFieldTypes;
use RecursiveIteratorIterator;
use SimpleXMLElement;

class TranscribeBlocksAction
{
    protected const string Input_Blocks_Dir = 'input/src/resources/blocks/';
    protected const string Output_Blocks_Dir = 'output/app/Cms/Blocks/';

    private RecursiveIteratorIterator $iterator;

    public function __construct()
    {
        try {
            $this->validateBlocksDirectory();
            $this->iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(self::Input_Blocks_Dir)
            );
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to initialize TranscribeBlocksAction: " . $e->getMessage(), 0, $e);
        }
    }

    private function validateBlocksDirectory(): void
    {
        if (!is_dir(self::Input_Blocks_Dir)) {
            throw new \InvalidArgumentException("The directory '" . (self::Input_Blocks_Dir) . "' does not exist.");
        }
        if (!is_readable(self::Input_Blocks_Dir)) {
            throw new \InvalidArgumentException("The directory '" . (self::Input_Blocks_Dir) . "' is not readable.");
        }
    }


    public function execute(): void
    {
        $blockXmlFiles = $this->getBlockXmlFiles();
        foreach ($blockXmlFiles as $file) {
            $this->parseBlockXmlFile($file);

        }
    }

    private function getBlockXmlFiles(): array
    {
        $xmlFiles = [];

        foreach ($this->iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'xml') {
                $xmlFiles[] = $file->getPathname();
            }
        }

        return $xmlFiles;
    }

    private function parseBlockXmlFile(string $file): void
    {
        $xml = new SimpleXMLElement(file_get_contents($file));
        $blockName = $this->sanitizeBlockName($file);

        $classArray = $this->generateBlockClass($xml, $blockName);
        $classString = implode("\n", $classArray);

        $parts = explode('/', $blockName);
        $capitalizedParts = array_map('ucwords', $parts);

        $outputDir = self::Output_Blocks_Dir . implode('/', array_slice($capitalizedParts, 0, -1));
        $phpFile = self::Output_Blocks_Dir . implode('/', $capitalizedParts) . '.php';

        if (!is_dir($outputDir)) {
            if (!mkdir($outputDir, 0777, true) && !is_dir($outputDir)) {
                throw new \RuntimeException("Failed to create directory: {$outputDir}");
            }
        }

        file_put_contents($phpFile, $classString);
    }


    private function sanitizeBlockName(string $file): string
    {
        $startPos = strpos($file, 'blocks/') + strlen('blocks/');
        $blockName = substr($file, $startPos);

        if (str_ends_with($blockName, '.xml')) {
            $blockName = substr($blockName, 0, -4);
        }

        return $blockName;
    }

    private function generateBlockClass(SimpleXMLElement $xml, string $blockName): array
    {
        $namespace = $this->generateNameSpace($blockName);
        $className = (string)$xml->attributes()->name;
        $blockStatements = $this->generateGetBlockMethod($xml, $blockName);

        $usesStatements = [];
        foreach ($xml->children() as $attributes) {
            $usesStatements[] = FormFieldTypes::getUses((string)$attributes['type']);
        }

        return array_merge(
            ["<?php",
                "namespace {$namespace};",
                "use App\Cms\Blocks\Interfaces\HasBlockSchema;",
                "use Filament\Forms\Components\Builder\Block;"
            ],
            $usesStatements,
            [
                "class {$className} implements HasBlockSchema",
                "{"
            ],
            $blockStatements,
            ["}"]
        );
    }

    private function generateGetBlockMethod(SimpleXMLElement $block, string $blockName): array
    {
        $formFieldGenerators = [];
        foreach ($block->children() as $attributes) {
            $formFieldGenerators[] = match ((string)$attributes['type']) {
                FormFieldTypes::TextField->value =>
                new TranscribeTextFormFieldAction($attributes),
                FormFieldTypes::RichTextField->value =>
                new TranscribeRichTextFormFieldAction($attributes),
                default => throw new \Exception('Unexpected match value'),
            };
        }

        $schemaFields = array_merge(
            ...array_map(fn($generator) => $generator->formFieldToArray(), $formFieldGenerators)
        );
        $resolverRecords = array_merge(
            ...array_map(fn($generator) => $generator->resolveRecordsToArray(), $formFieldGenerators)
        );

        return array_merge(
            [
                "public static function getBlock(): Block",
                "{",
                "return Block::make('{$blockName}')",
                "->label('{$block->attributes()->name}')",
                "->schema(["
            ],
            $schemaFields,
            [
                "]);",
                "}",
                "public static function resolve(array \$blockData): array",
                "{",
                "return ["
            ],
            $resolverRecords,
            [
                "];",
                "}"
            ]
        );

    }

    private function generateNameSpace(string $blockName): string
    {
        $parts = explode('/', $blockName);
        array_pop($parts);
        $capitalizedParts = array_map('ucwords', $parts);
        return implode('\\', array_merge(['App', 'Cms', 'Blocks'], $capitalizedParts));
    }
}
