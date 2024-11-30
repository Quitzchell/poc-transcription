<?php

namespace Actions\FormFields\Abstracts;

use SimpleXMLElement;

abstract class FormFieldGenerator
{
    protected array $attributes;

    public function __construct(SimpleXMLElement $attributes)
    {
        $this->attributes = $this->prepareAttributes($attributes);
    }

    abstract public function formFieldToArray(): array;

    public function resolveRecordsToArray(): array
    {
        $name = $this->attributes['name'];
        return ["'{$name}' => \$blockData['{$name}'],"];
    }

    public function prepareAttributes(SimpleXMLElement $attributes): array
    {
        return [
            'type' => (string)$attributes['type'],
            'name' => (string)$attributes['name'],
            'label' => (string)$attributes['title'],
        ];
    }
}