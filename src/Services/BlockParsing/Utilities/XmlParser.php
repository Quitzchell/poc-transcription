<?php

namespace App\Services\BlockParsing\Utilities;

use RuntimeException;
use SimpleXMLElement;

class XmlParser
{
    public function parseFile(string $filePath): SimpleXMLElement
    {
        try {
            return new SimpleXMLElement(file_get_contents($filePath));
        } catch (\Exception $e) {
            throw new RuntimeException("Failed to parse the file at path: {$filePath}", 0, $e);
        }
    }
}