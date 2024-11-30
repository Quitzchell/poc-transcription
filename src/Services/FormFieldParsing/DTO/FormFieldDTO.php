<?php

namespace App\Services\FormFieldParsing\DTO;

class FormFieldDTO
{
    private array $useStatements;
    private array $formSchema;
    private array $resolveSchema;

    public function __construct(array $useStatements, array $formSchema, array $resolveSchema)
    {
        $this->useStatements = $useStatements;
        $this->formSchema = $formSchema;
        $this->resolveSchema = $resolveSchema;
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