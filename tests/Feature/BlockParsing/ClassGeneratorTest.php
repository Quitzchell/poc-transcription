<?php

use App\Services\BlockParsing\Utilities\ClassGenerator;
use App\Services\BlockParsing\DTO\BlockDTO;

it('generates class code with correct structure', function () {
    // Arrange
    $blockData = new BlockDTO(
        blockName: 'example/block',
        blockTitle: 'ExampleBlock',
        useStatements: [
            "use App\Cms\Blocks\BaseBlock;",
        ],
        formSchema: [
            "TextInput::make('title')",
            "->label('Title')",
        ],
        resolveSchema: [
            "'title' => \$blockData['title']",
        ]
    );

    $classGenerator = new ClassGenerator();

    $expectedClassCode = file_get_contents(__DIR__ . '/Fixtures/expectedExampleBlock');

    // Act
    $classCode = $classGenerator->generate($blockData);

    // Assert
    expect(trim($classCode))->toBe(trim($expectedClassCode));
});

it('writes class code to a file', function () {
    // Arrange
    $classCode = "<?php\nnamespace App\\Cms\\Blocks;\nclass ExampleBlock {}";
    $outputPath = __DIR__ . '/ExampleBlock.php';

    $classGenerator = new ClassGenerator();

    // Act
    $classGenerator->writeClassFile($classCode, $outputPath);

    // Assert
    expect(file_exists($outputPath))->toBeTrue()
        ->and(file_get_contents($outputPath))->toBe($classCode);

    // Clean up
    unlink($outputPath);
});

it('generates correct namespace from block name', function () {
    // Arrange
    $blockName = 'example/sub/example-block';
    $classGenerator = new ClassGenerator();

    // Act
    $namespace = $classGenerator->generateBlockNameSpace($blockName);

    // Assert
    expect($namespace)->toBe('App\\Cms\\Blocks\\Example\\Sub');
});

it('handles block name without subdirectories correctly', function () {
    // Arrange
    $blockName = 'example-block';
    $classGenerator = new ClassGenerator();

    // Act
    $namespace = $classGenerator->generateBlockNameSpace($blockName);

    // Assert
    expect($namespace)->toBe('App\\Cms\\Blocks');
});
