<?php

namespace App\Services\BlockParsing;

use RuntimeException;
use SimpleXMLElement;

class XmlParser
{
    public function parseFile(string $filePath): SimpleXMLElement
    {
        try {
            $content = file_get_contents($filePath);
            if ($content === false) {
                throw new RuntimeException("Unable to read the file at path: {$filePath}");
            }

            return new SimpleXMLElement($content);
        } catch (\Exception $e) {
            throw new RuntimeException("Failed to parse the file at path: {$filePath}", 0, $e);
        }
    }
}