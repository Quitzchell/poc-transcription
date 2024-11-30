<?php

namespace Actions\FormFields;

use Actions\FormFields\Abstracts\FormFieldGenerator;
use Enums\RichTextToolbarOptions;
use SimpleXMLElement;

class TranscribeRichTextFormFieldAction extends FormFieldGenerator
{
    public function formFieldToArray(): array
    {
        $toolbarButtons = implode(", ", array_map(function ($item) {
            return "'{$item}'"; // Add quotes around each item
        }, $this->attributes['toolbarButtons']));

        // Return the formatted string
        return [
            "RichEditor::make('{$this->attributes['name']}')",
            "->label('{$this->attributes['label']}')",
            "->toolbarButtons([",
            "{$toolbarButtons}",
            "]),"
        ];
    }

    public function prepareAttributes(SimpleXMLElement $attributes): array
    {
        $default = parent::prepareAttributes($attributes);
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