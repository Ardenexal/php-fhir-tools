<?php

declare(strict_types=1);

namespace Tests\Unit\Component\Models;

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for FHIR Models component package structure validation.
 *
 * Tests that the Models component follows the established component pattern
 * with proper composer.json structure, directory organization, and documentation.
 *
 * @author FHIR Tools Contributors
 */
class ModelsPackageStructureTest extends TestCase
{
    private string $componentPath;

    protected function setUp(): void
    {
        $this->componentPath = __DIR__ . '/../../../../src/Component/Models';
    }

    /**
     * Test composer.json structure and metadata.
     *
     * **Validates: Requirements 6.1, 6.2**
     */
    public function testComposerJsonStructureAndMetadata(): void
    {
        $composerPath = $this->componentPath . '/composer.json';

        self::assertFileExists($composerPath, 'composer.json should exist in Models component');

        $composerContent = file_get_contents($composerPath);
        self::assertNotFalse($composerContent, 'composer.json should be readable');

        $composerData = json_decode($composerContent, true);
        self::assertIsArray($composerData, 'composer.json should contain valid JSON');

        // Test required package metadata
        self::assertArrayHasKey('name', $composerData);
        self::assertEquals('ardenexal/fhir-models', $composerData['name']);

        self::assertArrayHasKey('description', $composerData);
        self::assertStringContainsString('FHIR model classes', $composerData['description']);
        self::assertStringContainsString('R4', $composerData['description']);
        self::assertStringContainsString('R4B', $composerData['description']);
        self::assertStringContainsString('R5', $composerData['description']);

        self::assertArrayHasKey('type', $composerData);
        self::assertEquals('library', $composerData['type']);

        self::assertArrayHasKey('license', $composerData);
        self::assertEquals('MIT', $composerData['license']);

        // Test authors
        self::assertArrayHasKey('authors', $composerData);
        self::assertIsArray($composerData['authors']);
        self::assertNotEmpty($composerData['authors']);

        $author = $composerData['authors'][0];
        self::assertArrayHasKey('name', $author);
        self::assertEquals('FHIR Tools Contributors', $author['name']);
        self::assertArrayHasKey('email', $author);
        self::assertEquals('contributors@fhirtools.dev', $author['email']);

        // Test stability preferences
        self::assertArrayHasKey('minimum-stability', $composerData);
        self::assertEquals('stable', $composerData['minimum-stability']);

        self::assertArrayHasKey('prefer-stable', $composerData);
        self::assertTrue($composerData['prefer-stable']);

        // Test minimal dependencies
        self::assertArrayHasKey('require', $composerData);
        self::assertArrayHasKey('php', $composerData['require']);
        self::assertEquals('>=8.2', $composerData['require']['php']);

        // Verify minimal dependencies (only PHP required)
        self::assertCount(1, $composerData['require'], 'Models component should have minimal dependencies');

        // Test dev dependencies
        self::assertArrayHasKey('require-dev', $composerData);
        self::assertArrayHasKey('phpunit/phpunit', $composerData['require-dev']);
        self::assertArrayHasKey('giorgiosironi/eris', $composerData['require-dev']);

        // Test autoload configuration
        self::assertArrayHasKey('autoload', $composerData);
        self::assertArrayHasKey('psr-4', $composerData['autoload']);
        self::assertArrayHasKey('Ardenexal\\FHIRTools\\Component\\Models\\', $composerData['autoload']['psr-4']);
        self::assertEquals('src/', $composerData['autoload']['psr-4']['Ardenexal\\FHIRTools\\Component\\Models\\']);

        // Test autoload-dev configuration
        self::assertArrayHasKey('autoload-dev', $composerData);
        self::assertArrayHasKey('psr-4', $composerData['autoload-dev']);
        self::assertArrayHasKey('Ardenexal\\FHIRTools\\Component\\Models\\Tests\\', $composerData['autoload-dev']['psr-4']);
        self::assertEquals('tests/', $composerData['autoload-dev']['psr-4']['Ardenexal\\FHIRTools\\Component\\Models\\Tests\\']);

        // Test scripts
        self::assertArrayHasKey('scripts', $composerData);
        self::assertArrayHasKey('test', $composerData['scripts']);
        self::assertEquals('phpunit', $composerData['scripts']['test']);
        self::assertArrayHasKey('test-coverage', $composerData['scripts']);
        self::assertEquals('phpunit --coverage-html coverage', $composerData['scripts']['test-coverage']);

        // Test keywords
        self::assertArrayHasKey('keywords', $composerData);
        self::assertIsArray($composerData['keywords']);
        self::assertContains('fhir', $composerData['keywords']);
        self::assertContains('models', $composerData['keywords']);
        self::assertContains('r4', $composerData['keywords']);
        self::assertContains('r4b', $composerData['keywords']);
        self::assertContains('r5', $composerData['keywords']);

        // Test homepage and support
        self::assertArrayHasKey('homepage', $composerData);
        self::assertArrayHasKey('support', $composerData);
        self::assertArrayHasKey('issues', $composerData['support']);
        self::assertArrayHasKey('source', $composerData['support']);
    }

    /**
     * Test directory structure compliance.
     *
     * **Validates: Requirements 6.1, 6.3**
     */
    public function testDirectoryStructureCompliance(): void
    {
        // Test main component directory exists
        self::assertDirectoryExists($this->componentPath, 'Models component directory should exist');

        // Test required files exist
        self::assertFileExists($this->componentPath . '/composer.json', 'composer.json should exist');
        self::assertFileExists($this->componentPath . '/README.md', 'README.md should exist');
        self::assertFileExists($this->componentPath . '/.gitignore', '.gitignore should exist');

        // Test directory structure
        self::assertDirectoryExists($this->componentPath . '/src', 'src directory should exist');
        self::assertDirectoryExists($this->componentPath . '/tests', 'tests directory should exist');

        // Test .gitkeep files exist to ensure directories are tracked
        self::assertFileExists($this->componentPath . '/tests/.gitkeep', 'tests/.gitkeep should exist');

        // Test .gitignore content
        $gitignoreContent = file_get_contents($this->componentPath . '/.gitignore');
        self::assertNotFalse($gitignoreContent, '.gitignore should be readable');

        // Verify utility classes are preserved
        self::assertStringContainsString('!src/Utility/', $gitignoreContent);

        // Verify common ignore patterns
        self::assertStringContainsString('/vendor/', $gitignoreContent);
        self::assertStringContainsString('/coverage/', $gitignoreContent);
        self::assertStringContainsString('/.phpunit.cache/', $gitignoreContent);
    }

    /**
     * Test README.md completeness.
     *
     * **Validates: Requirements 6.3**
     */
    public function testReadmeCompleteness(): void
    {
        $readmePath = $this->componentPath . '/README.md';

        self::assertFileExists($readmePath, 'README.md should exist');

        $readmeContent = file_get_contents($readmePath);
        self::assertNotFalse($readmeContent, 'README.md should be readable');
        self::assertNotEmpty($readmeContent, 'README.md should not be empty');

        // Test required sections
        self::assertStringContainsString('# FHIR Models Component', $readmeContent);
        self::assertStringContainsString('## Features', $readmeContent);
        self::assertStringContainsString('## Installation', $readmeContent);
        self::assertStringContainsString('## Quick Start', $readmeContent);
        self::assertStringContainsString('## Architecture', $readmeContent);
        self::assertStringContainsString('## Core Components', $readmeContent);
        self::assertStringContainsString('## FHIR Version Support', $readmeContent);
        self::assertStringContainsString('## Model Types', $readmeContent);
        self::assertStringContainsString('## Component Integration', $readmeContent);
        self::assertStringContainsString('## Advanced Usage', $readmeContent);
        self::assertStringContainsString('## Testing', $readmeContent);
        self::assertStringContainsString('## Error Handling', $readmeContent);
        self::assertStringContainsString('## Requirements', $readmeContent);
        self::assertStringContainsString('## Documentation', $readmeContent);
        self::assertStringContainsString('## License', $readmeContent);

        // Test key features are documented
        self::assertStringContainsString('Pre-Generated Models', $readmeContent);
        self::assertStringContainsString('Version Isolation', $readmeContent);
        self::assertStringContainsString('Comprehensive Coverage', $readmeContent);
        self::assertStringContainsString('Minimal Dependencies', $readmeContent);
        self::assertStringContainsString('Component Integration', $readmeContent);

        // Test FHIR versions are documented
        self::assertStringContainsString('R4', $readmeContent);
        self::assertStringContainsString('R4B', $readmeContent);
        self::assertStringContainsString('R5', $readmeContent);

        // Test installation instructions
        self::assertStringContainsString('composer require ardenexal/fhir-models', $readmeContent);
        self::assertStringContainsString('composer require ardenexal/fhir-bundle', $readmeContent);

        // Test namespace documentation
        self::assertStringContainsString('Ardenexal\\FHIRTools\\Component\\Models\\', $readmeContent);
        self::assertStringContainsString('Resource\\', $readmeContent);
        self::assertStringContainsString('DataType\\', $readmeContent);
        self::assertStringContainsString('Primitive\\', $readmeContent);
        self::assertStringContainsString('Enum\\', $readmeContent);
        self::assertStringContainsString('Utility\\', $readmeContent);

        // Test utility classes are documented
        self::assertStringContainsString('ModelRegistry', $readmeContent);
        self::assertStringContainsString('VersionDetector', $readmeContent);

        // Test code examples are present
        self::assertStringContainsString('<?php', $readmeContent);
        self::assertStringContainsString('use Ardenexal\\FHIRTools\\Component\\Models\\', $readmeContent);

        // Test requirements are documented
        self::assertStringContainsString('PHP**: 8.2 or higher', $readmeContent);
        self::assertStringContainsString('Dependencies**: None', $readmeContent);

        // Test license information
        self::assertStringContainsString('MIT License', $readmeContent);
    }

    /**
     * Test that the component follows the established pattern.
     *
     * **Validates: Requirements 6.1, 6.2, 6.3**
     */
    public function testComponentFollowsEstablishedPattern(): void
    {
        // Compare with existing components to ensure consistency
        $codeGenerationPath = __DIR__ . '/../../../../src/Component/CodeGeneration';
        $serializationPath  = __DIR__ . '/../../../../src/Component/Serialization';

        // Test that Models component has same basic structure as other components
        if (is_dir($codeGenerationPath)) {
            self::assertFileExists($codeGenerationPath . '/composer.json');
            self::assertFileExists($codeGenerationPath . '/README.md');
            self::assertDirectoryExists($codeGenerationPath . '/src');
        }

        if (is_dir($serializationPath)) {
            self::assertFileExists($serializationPath . '/composer.json');
            self::assertFileExists($serializationPath . '/README.md');
            self::assertDirectoryExists($serializationPath . '/src');
        }

        // Test Models component has same structure
        self::assertFileExists($this->componentPath . '/composer.json');
        self::assertFileExists($this->componentPath . '/README.md');
        self::assertDirectoryExists($this->componentPath . '/src');
        self::assertDirectoryExists($this->componentPath . '/tests');

        // Test composer.json follows same pattern
        $modelsComposer = json_decode(file_get_contents($this->componentPath . '/composer.json'), true);

        // Test package name follows pattern
        self::assertStringStartsWith('ardenexal/fhir-', $modelsComposer['name']);

        // Test namespace follows pattern
        $autoload  = $modelsComposer['autoload']['psr-4'];
        $namespace = array_keys($autoload)[0];
        self::assertStringStartsWith('Ardenexal\\FHIRTools\\Component\\', $namespace);
        self::assertStringEndsWith('\\', $namespace);

        // Test autoload path is 'src/'
        self::assertEquals('src/', $autoload[$namespace]);

        // Test dev dependencies include PHPUnit and Eris
        self::assertArrayHasKey('phpunit/phpunit', $modelsComposer['require-dev']);
        self::assertArrayHasKey('giorgiosironi/eris', $modelsComposer['require-dev']);

        // Test scripts include test commands
        self::assertArrayHasKey('test', $modelsComposer['scripts']);
        self::assertArrayHasKey('test-coverage', $modelsComposer['scripts']);
    }

    /**
     * Test package metadata completeness for publication.
     *
     * **Validates: Requirements 6.5**
     */
    public function testPackageMetadataCompletenessForPublication(): void
    {
        $composerPath = $this->componentPath . '/composer.json';
        $composerData = json_decode(file_get_contents($composerPath), true);

        // Test all required fields for Packagist publication
        $requiredFields = [
            'name',
            'description',
            'type',
            'license',
            'authors',
            'require',
            'autoload',
        ];

        foreach ($requiredFields as $field) {
            self::assertArrayHasKey($field, $composerData, "composer.json should have {$field} field for publication");
        }

        // Test package name is valid for Packagist
        self::assertMatchesRegularExpression(
            '/^[a-z0-9]([_.-]?[a-z0-9]+)*\/[a-z0-9]([_.-]?[a-z0-9]+)*$/',
            $composerData['name'],
            'Package name should be valid for Packagist',
        );

        // Test description is meaningful
        self::assertGreaterThan(20, strlen($composerData['description']), 'Description should be meaningful');

        // Test license is valid SPDX identifier
        self::assertEquals('MIT', $composerData['license'], 'License should be valid SPDX identifier');

        // Test authors have required fields
        foreach ($composerData['authors'] as $author) {
            self::assertArrayHasKey('name', $author, 'Author should have name');
            self::assertArrayHasKey('email', $author, 'Author should have email');
        }

        // Test keywords are present and relevant
        self::assertArrayHasKey('keywords', $composerData, 'Package should have keywords for discoverability');
        self::assertIsArray($composerData['keywords']);
        self::assertNotEmpty($composerData['keywords']);

        // Test support information is present
        self::assertArrayHasKey('support', $composerData, 'Package should have support information');
        self::assertArrayHasKey('issues', $composerData['support']);
        self::assertArrayHasKey('source', $composerData['support']);

        // Test homepage is present
        self::assertArrayHasKey('homepage', $composerData, 'Package should have homepage');

        // Test autoload is properly configured
        $autoload = $composerData['autoload']['psr-4'];
        self::assertCount(1, $autoload, 'Should have exactly one PSR-4 autoload entry');

        $namespace = array_keys($autoload)[0];
        $path      = $autoload[$namespace];

        self::assertStringEndsWith('\\', $namespace, 'Namespace should end with backslash');
        self::assertEquals('src/', $path, 'Autoload path should be src/');

        // Test dev autoload is properly configured
        if (isset($composerData['autoload-dev'])) {
            $devAutoload = $composerData['autoload-dev']['psr-4'];
            self::assertCount(1, $devAutoload, 'Should have exactly one dev PSR-4 autoload entry');

            $devNamespace = array_keys($devAutoload)[0];
            $devPath      = $devAutoload[$devNamespace];

            self::assertStringEndsWith('\\', $devNamespace, 'Dev namespace should end with backslash');
            self::assertEquals('tests/', $devPath, 'Dev autoload path should be tests/');
        }
    }
}
