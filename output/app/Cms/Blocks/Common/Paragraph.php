<?php

namespace App\Cms\Blocks;

use App\Cms\Blocks\Interfaces\HasBlockSchema;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;

class Paragraph implements HasBlockSchema
{
    public static function getBlock(): Block
    {
        return Block::make('common\paragraph')
            ->label('Paragraph')
            ->schema([
                TextInput::make('title')
                    ->label('Title'),
                RichEditor::make('text')
                    ->label('Text')
                    ->toolbarButtons([
                        'h1',
                        'h2',
                        'h3',
                        'bold',
                        'italic',
                        'underline',
                        'orderedList',
                        'bulletList',
                        'link'
                    ]),
            ]);
    }

    public static function resolve(array $blockData): array
    {
        return [
            'title' => $blockData['title'],
            'text' => $blockData['text'],
        ];
    }
}