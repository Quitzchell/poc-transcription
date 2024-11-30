<?php

namespace App\Services\BlockParsing\Utilities;

use App\Services\BlockParsing\DTO\BlockDTO;

class ClassGenerator
{
    public function generate(BlockDTO $blockData): string
    {
        $namespace = $this->generateBlockNameSpace($blockData->getBlockName());
        $classArray = array_merge(
            [
                "<?php",
                "namespace {$namespace};",
                "use App\Cms\Blocks\Interfaces\HasBlockSchema;",
                "use Filament\Forms\Components\Builder\Block;"
            ],
            $blockData->getUseStatements(),
            [
                "class {$blockData->getBlockTitle()} implements HasBlockSchema",
                "{"
            ],
            [
                "public static function getBlock(): Block",
                "{",
                "return Block::make('{$blockData->getBlockName()}')",
                "->label('{$blockData->getBlockTitle()}')",
                "->schema(["
            ],
            $blockData->getFormSchema(),
            [
                "]);",
                "}",
                "public static function resolve(array \$blockData): array",
                "{",
                "return ["
            ],
            $blockData->getResolveSchema(),
            [
                "];",
                "}",
                '}'
            ]
        );

        return implode("\n", $classArray);
    }

    public function writeClassFile(string $classCode, string $outputPath): void
    {
        file_put_contents($outputPath, $classCode);
    }

    private function generateBlockNameSpace(string $blockName): string
    {
        $parts = explode('/', $blockName);
        array_pop($parts);
        $capitalizedParts = array_map('ucwords', $parts);
        return implode('\\', array_merge(['App', 'Cms', 'Blocks'], $capitalizedParts));
    }
}