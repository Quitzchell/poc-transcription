<?php

use App\Services\BlockParsing\Utilities\XmlParser;

it('parses a valid XML file successfully', function () {
    // Arrange
    $filePath = __DIR__ . '/fixtures/valid.xml';
    $parser = new XmlParser();

    // Act
    $result = $parser->parseFile($filePath);

    // Assert
    expect($result)->toBeInstanceOf(SimpleXMLElement::class)
        ->and((string) $result->attributes()->name)->toBe('Test');
});

it('throws an exception when the file is empty', function () {
    // Arrange
    $filePath = __DIR__ . '/fixtures/empty.xml';
    $parser = new XmlParser();

    // Act & Assert
    expect(fn () => $parser->parseFile($filePath))
        ->toThrow(RuntimeException::class, "Failed to parse the file at path: {$filePath}");
});
