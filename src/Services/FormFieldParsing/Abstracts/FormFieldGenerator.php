<?php

namespace App\Services\FormFieldParsing\Abstracts;

use SimpleXMLElement;

abstract class FormFieldGenerator
{
    protected array $attributes;

    public function __construct(SimpleXMLElement $attributes)
    {
        $this->attributes = $this->extractAttributes($attributes);
    }

    public function extractAttributes(SimpleXMLElement $attributes): array
    {
        return [
            'type' => (string)$attributes['type'],
            'name' => (string)$attributes['name'],
            'label' => (string)$attributes['title'],
        ];
    }

    abstract public function generateUseStatement(): array;

    abstract public function generateBlockSchema(): array;

    public function generateResolveSchema(): array
    {
        $name = $this->attributes['name'];
        return ["'{$name}' => \$blockData['{$name}'],"];
    }

}