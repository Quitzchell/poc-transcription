<?php

namespace Actions\FormFields;

use Actions\FormFields\Abstracts\FormFieldGenerator;
use SimpleXMLElement;

class TranscribeTextFormFieldAction extends FormFieldGenerator
{
    public function formFieldToArray(): array
    {
        return [
            "TextInput::make('{$this->attributes['name']}')",
            "->label('{$this->attributes['label']}'),"
        ];
    }

    public function prepareAttributes(SimpleXMLElement $attributes): array
    {
        $default = parent::prepareAttributes($attributes);
        return array_merge($default, []);
    }
}