<?php

use App\Services\BlockParsing\Utilities\FileProcessor;

it('sets the correct input and output directories in the constructor', function () {
    // Arrange
    $inputDir = __DIR__ . '/input';
    $outputDir = __DIR__ . '/output';

    $fileProcessor = new FileProcessor($inputDir, $outputDir);

    // Assert
    expect($fileProcessor->getInputDir())->toEqual($inputDir)
        ->and($fileProcessor->getOutputDir())->toEqual($outputDir);
});

it('validates a valid directory', function () {
    // Arrange
    $inputDir = __DIR__ . '/input';
    $outputDir = __DIR__ . '/output';
    mkdir($inputDir, 0777, true);

    $fileProcessor = new FileProcessor($inputDir, $outputDir);

    // Act & Assert
    expect($fileProcessor->validateDirectory($inputDir))->toBeTrue();

    // Clean up
    rmdir($inputDir);
});

it('throws an exception for an invalid directory', function () {
    // Arrange
    $inputDir = __DIR__ . '/input';
    $outputDir = __DIR__ . '/output';
    mkdir($inputDir, 0777, true);

    $fileProcessor = new FileProcessor($inputDir, $outputDir);

    // Act & Assert
    expect(fn() => $fileProcessor->validateDirectory($outputDir))
        ->toThrow(\InvalidArgumentException::class, "The directory '$outputDir' does not exist.");

    // Clean up
    rmdir($inputDir);
});

it('retrieves only xml files from the input directory', function () {
    // Arrange
    $inputDir = __DIR__ . '/input';
    mkdir($inputDir, 0777, true);
    file_put_contents($inputDir . '/file1.xml', '<xml></xml>');
    file_put_contents($inputDir . '/file2.xml', '<xml></xml>');
    file_put_contents($inputDir . '/file3.txt', 'Some text content');

    $fileProcessor = new FileProcessor($inputDir, __DIR__ . '/output');

    // Act
    $xmlFiles = $fileProcessor->getXmlFiles();

    // Assert
    expect($xmlFiles)->toHaveCount(2)
        ->and($xmlFiles)->toContain($inputDir . '/file1.xml')
        ->and($xmlFiles)->toContain($inputDir . '/file2.xml');

    // Clean up
    unlink($inputDir . '/file1.xml');
    unlink($inputDir . '/file2.xml');
    unlink($inputDir . '/file3.txt');
    rmdir($inputDir);
});

it('creates a new directory if it does not exist', function () {
    // Arrange
    $outputDir = __DIR__ . '/output';
    $newDir = __DIR__ . '/output/new_testing_directory/';
    $fileProcessor = new FileProcessor(__DIR__ . '/input',  $outputDir);

    // Act
    $fileProcessor->ensureDirectoryExists($newDir);

    // Assert
    expect(is_dir($newDir))->toBeTrue();

    // Clean up
    rmdir($newDir);
    rmdir($outputDir);
});
