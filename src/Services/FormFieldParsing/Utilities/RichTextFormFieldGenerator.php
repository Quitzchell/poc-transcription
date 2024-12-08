<?php

namespace App\Services\FormFieldParsing\Utilities;

use App\Enums\RichTextToolbarOptions;
use App\Services\FormFieldParsing\Utilities\Abstracts\FormFieldGenerator;
use SimpleXMLElement;

class RichTextFormFieldGenerator extends FormFieldGenerator
{
    public function generateUseStatement(): array
    {
        return ["use Filament\Forms\Components\RichEditor;"];
    }

    public function generateBlockSchema(): array
    {
        $toolbarButtons = implode(",\n", array_map(function ($item) {
            return "'{$item}'";
        }, $this->attributes['toolbarButtons']));

        return [
            "RichEditor::make('{$this->attributes['name']}')",
            "->label('{$this->attributes['label']}')",
            "->toolbarButtons([",
            "{$toolbarButtons}",
            "]),"
        ];
    }

    public function extractAttributes(SimpleXMLElement $attributes): array
    {
        $default = parent::extractAttributes($attributes);
        if ($attributes['toolbar']) {
            $toolbarAttributes = $this->mapToolbarAttributes($attributes['toolbar']);
        }

        return array_merge($default, [
            'toolbarButtons' => $toolbarAttributes,
        ]);
    }

    private function mapToolbarAttributes($toolbar): array
    {
        $toolbarOptionList = array_filter(
            explode(" ", $toolbar),
            fn($item) => !str_contains(trim($item), '|')
        );

        $mappedToolbarOptions = [];

        foreach ($toolbarOptionList as $option) {
            $mappedOption = match ($option) {
                RichTextToolbarOptions::FormatSelect->value => ['h1', 'h2', 'h3'],
                RichTextToolbarOptions::Bold->value => ['bold'],
                RichTextToolbarOptions::Italic->value => ['italic'],
                RichTextToolbarOptions::Underline->value => ['underline'],
                RichTextToolbarOptions::NumList->value => ['orderedList'],
                RichTextToolbarOptions::BullList->value => ['bulletList'],
                RichTextToolbarOptions::link->value => ['link'],
                default => null, // Return null for unmatched cases
            };

            if (is_null($mappedOption)) {
                trigger_error("Warning: Unknown toolbar option: $option", E_USER_WARNING);
            } else {
                $mappedToolbarOptions[] = $mappedOption;
            }
        }

        return array_merge(...$mappedToolbarOptions);
    }
}
