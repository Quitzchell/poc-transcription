<?php

namespace App\Services\FormFieldParsing;

use App\Services\FormFieldParsing\Abstracts\FormFieldGenerator;
use SimpleXMLElement;

class TextFormFieldGenerator extends FormFieldGenerator
{
    public function generateUseStatement(): array
    {
        return ["use Filament\Forms\Components\TextInput;"];
    }

    public function generateBlockSchema(): array
    {
        return [
            "TextInput::make('{$this->attributes['name']}')",
            "->label('{$this->attributes['label']}'),"
        ];
    }

    public function extractAttributes(SimpleXMLElement $attributes): array
    {
        $default = parent::extractAttributes($attributes);
        return array_merge($default, []);
    }
}