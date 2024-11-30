<?php

namespace App\Services\FormFieldParsing;

use App\Enums\FormFieldTypes;
use App\Services\FormFieldParsing\DTO\FormFieldDTO;
use App\Services\FormFieldParsing\Utilities\Abstracts\FormFieldGenerator;
use App\Services\FormFieldParsing\Utilities\RichTextFormFieldGenerator;
use App\Services\FormFieldParsing\Utilities\TextFormFieldGenerator;
use Exception;
use SimpleXMLElement;

class FormFieldTranscriptionService
{
    public function process(SimpleXMLElement $xml): FormFieldDTO
    {
        $generators = $this->generateFieldGenerators($xml);

        return new FormFieldDTO(
            $this->mergeGeneratorData($generators, 'generateUseStatement'),
            $this->mergeGeneratorData($generators, 'generateBlockSchema'),
            $this->mergeGeneratorData($generators, 'generateResolveSchema')
        );
    }

    private function generateFieldGenerators(SimpleXMLElement $xml): array
    {
        $generators = [];

        foreach ($xml->children() as $attributes) {
            $type = (string)$attributes['type'];
            $generators[] = $this->createGeneratorByType($type, $attributes);
        }

        return $generators;
    }

    private function createGeneratorByType(string $type, SimpleXMLElement $attributes): FormFieldGenerator
    {
        return match ($type) {
            FormFieldTypes::TextField->value => new TextFormFieldGenerator($attributes),
            FormFieldTypes::RichTextField->value => new RichTextFormFieldGenerator($attributes),
            default => throw new Exception("Unexpected field type: {$type}"),
        };
    }

    private function mergeGeneratorData(array $generators, string $method): array
    {
        return array_merge(
            ...array_map(function($generator) use ($method) {
                $result = $generator->$method();
                return is_array($result) ? $result : [];
            }, $generators)
        );
    }
}