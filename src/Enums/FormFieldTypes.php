<?php

namespace Enums;

enum FormFieldTypes: string
{
    case TextField = 'text';
    case RichTextField = 'html';

    public static function getUses(string $value): string
    {
        return match ($value) {
            self::TextField->value => "use Filament\Forms\Components\TextInput;",
            self::RichTextField->value => "use Filament\Forms\Components\RichEditor;"
        };
    }
}

