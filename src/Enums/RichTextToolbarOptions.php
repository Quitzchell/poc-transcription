<?php

namespace Enums;

enum RichTextToolbarOptions: string
{
    case FormatSelect = 'formatselect';
    case Bold = 'bold';
    case Italic = 'italic';
    case Underline = 'underline';
    case CharMap = 'charmap';
    case link = 'link';
    case BullList = 'bulllist';
    case NumList = 'numlist';
}