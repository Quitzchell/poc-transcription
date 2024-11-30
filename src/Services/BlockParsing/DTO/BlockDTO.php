<?php

namespace App\Services\BlockParsing\DTO;

class BlockDTO
{
    private string $blockName;
    private string $blockTitle;
    private array $useStatements;
    private array $formSchema;
    private array $resolveSchema;

    public function __construct(string $blockName, string $blockTitle, array $useStatements,array $formSchema, array $resolveSchema)
    {
        $this->blockName = $blockName;
        $this->blockTitle = $blockTitle;
        $this->useStatements = $useStatements;
        $this->formSchema = $formSchema;
        $this->resolveSchema = $resolveSchema;
    }

    public function getBlockName(): string
    {
        return $this->blockName;
    }

    public function getBlockTitle(): string
    {
        return $this->blockTitle;
    }

    public function getUseStatements(): array
    {
        return $this->useStatements;
    }

    public function getFormSchema(): array
    {
        return $this->formSchema;
    }

    public function getResolveSchema(): array
    {
        return $this->resolveSchema;
    }
}