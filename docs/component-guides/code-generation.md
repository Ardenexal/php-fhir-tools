# CodeGeneration Component Guide

## Overview

The CodeGeneration component is a standalone library for generating PHP classes from FHIR StructureDefinitions. It can be used independently or as part of the FHIRBundle in Symfony applications.

## Installation

### Standalone Installation

```bash
composer require ardenexal/fhir-code-generation
```

### With FHIRBundle

The CodeGeneration component is automatically included when you install the FHIRBundle:

```bash
composer require ardenexal/fhir-bundle
```

## Basic Usage

### Standalone Usage

```php
<?php

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;

// Create builder context
$context = new BuilderContext();
$context->setOutputDirectory('/path/to/output');
$context->setBaseNamespace('MyApp\\FHIR');

// Create package loader
$packageLoader = new PackageLoader();

// Create generator
$generator = new FHIRModelGenerator($context, $packageLoader);

// Generate FHIR models
$generator->generate('R4B');
```

### With Symfony DI

```php
<?php

namespace App\Service;

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;

class MyFHIRService
{
    public function __construct(
        private readonly FHIRModelGenerator $generator
    ) {}

    public function generateModels(string $version): void
    {
        $this->generator->generate($version);
    }
}
```

## Core Components

### FHIRModelGenerator

The main class responsible for generating FHIR model classes.

```php
<?php

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;

$context = new BuilderContext();
$context->setOutputDirectory('/path/to/output');
$context->setBaseNamespace('MyApp\\FHIR');

$generator = new FHIRModelGenerator($context);

// Generate all models for a FHIR version
$generator->generate('R4B');

// Generate specific resource types
$generator->generateResource('Patient');
$generator->generateResource('Observation');
```

### FHIRValueSetGenerator

Generates PHP enums from FHIR ValueSets.

```php
<?php

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRValueSetGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;

$context = new BuilderContext();
$generator = new FHIRValueSetGenerator($context);

// Generate value set enums
$generator->generateValueSet('administrative-gender');
$generator->generateValueSet('observation-status');
```

### BuilderContext

Configuration context for code generation.

```php
<?php

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;

$context = new BuilderContext();

// Basic configuration
$context->setOutputDirectory('/path/to/output');
$context->setBaseNamespace('MyApp\\FHIR');

// Advanced configuration
$context->setGenerateTests(true);
$context->setGenerateDocumentation(true);
$context->setStrictTypes(true);
$context->setPhpVersion('8.2');

// Custom templates
$context->setTemplateDirectory('/path/to/custom/templates');

// Error handling
$context->setStrictMode(true);
$context->setIgnoreErrors(false);
```

### PackageLoader

Loads and manages FHIR packages.

```php
<?php

use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageMetadata;

$loader = new PackageLoader();

// Load package from registry
$package = $loader->loadPackage('hl7.fhir.r4b.core', '4.3.0');

// Load package from local file
$package = $loader->loadFromFile('/path/to/package.tgz');

// Get package metadata
$metadata = $package->getMetadata();
echo $metadata->getName();
echo $metadata->getVersion();
echo $metadata->getDescription();
```

## Configuration Options

### BuilderContext Configuration

```php
<?php

$context = new BuilderContext();

// Output settings
$context->setOutputDirectory('/path/to/output');
$context->setBaseNamespace('MyApp\\FHIR');
$context->setFileExtension('.php');

// Code generation settings
$context->setGenerateTests(true);
$context->setGenerateDocumentation(true);
$context->setGenerateInterfaces(true);
$context->setGenerateTraits(false);

// PHP settings
// Note: Most configuration is handled by the generator classes
// BuilderContext manages generated types and namespaces

// Add namespaces for FHIR version
$elementNamespace = new PhpNamespace('MyApp\\FHIR\\Element');
$enumNamespace = new PhpNamespace('MyApp\\FHIR\\Enum');
$context->addElementNamespace('R4B', $elementNamespace);
$context->addEnumNamespace('R4B', $enumNamespace);
```

### Package Configuration

```php
<?php

use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;

$loader = new PackageLoader();

// Install package (automatically handles caching)
$metadata = $loader->installPackage('hl7.fhir.r4b.core', '4.3.0');

// Load to context
$loader->loadPackageToContext($metadata, 'R4B');

// Check cache statistics
$stats = $loader->getCacheStatistics();
```

## Advanced Usage

### Custom Templates

Create custom templates for generated code:

```php
<?php

// templates/class.php.twig
namespace {{ namespace }};

use Ardenexal\FHIRTools\Attributes\FHIRResource;

/**
 * {{ description }}
 * 
 * @author Generated by FHIR Tools
 */
#[FHIRResource(resourceType: '{{ resourceType }}')]
class {{ className }}
{
    {% for property in properties %}
    /**
     * {{ property.description }}
     */
    private {{ property.type }} ${{ property.name }};
    
    {% endfor %}
    
    {% for property in properties %}
    public function get{{ property.name|title }}(): {{ property.type }}
    {
        return $this->{{ property.name }};
    }
    
    public function set{{ property.name|title }}({{ property.type }} ${{ property.name }}): self
    {
        $this->{{ property.name }} = ${{ property.name }};
        return $this;
    }
    
    {% endfor %}
}
```

```php
<?php

$context = new BuilderContext();
$context->setTemplateDirectory('/path/to/templates');
$context->setTemplate('class', 'class.php.twig');

$generator = new FHIRModelGenerator($context);
$generator->generate('R4B');
```

### Custom Generators

Extend the base generator for custom functionality:

```php
<?php

namespace MyApp\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;

class CustomFHIRGenerator extends FHIRModelGenerator
{
    public function __construct(BuilderContext $context)
    {
        parent::__construct($context);
    }

    protected function generateClass(array $structureDefinition): string
    {
        // Custom class generation logic
        $code = parent::generateClass($structureDefinition);
        
        // Add custom methods or properties
        $code .= $this->generateCustomMethods($structureDefinition);
        
        return $code;
    }

    private function generateCustomMethods(array $structureDefinition): string
    {
        // Generate custom methods based on structure definition
        return "
    /**
     * Custom validation method
     */
    public function validate(): array
    {
        // Custom validation logic
        return [];
    }
        ";
    }
}
```

### Batch Generation

Generate multiple packages or versions:

```php
<?php

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;

class BatchGenerator
{
    private FHIRModelGenerator $generator;

    public function __construct(FHIRModelGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function generateMultipleVersions(array $versions): void
    {
        foreach ($versions as $version) {
            echo "Generating FHIR {$version}...\n";
            
            // Update output directory for each version
            $this->generator->getContext()->setOutputDirectory("/output/{$version}");
            $this->generator->getContext()->setBaseNamespace("FHIR\\{$version}");
            
            $this->generator->generate($version);
            
            echo "Completed FHIR {$version}\n";
        }
    }

    public function generateSpecificResources(string $version, array $resources): void
    {
        foreach ($resources as $resource) {
            echo "Generating {$resource}...\n";
            $this->generator->generateResource($resource);
        }
    }
}

// Usage
$batchGenerator = new BatchGenerator($generator);
$batchGenerator->generateMultipleVersions(['R4', 'R4B', 'R5']);
$batchGenerator->generateSpecificResources('R4B', ['Patient', 'Observation', 'Practitioner']);
```

## Error Handling

### Exception Types

The component defines several exception types:

```php
<?php

use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException;

try {
    $generator->generate('R4B');
} catch (GenerationException $e) {
    // Handle code generation errors
    echo "Generation error: " . $e->getMessage();
} catch (PackageException $e) {
    // Handle package loading errors
    echo "Package error: " . $e->getMessage();
} catch (\Exception $e) {
    // Handle other errors
    echo "Unexpected error: " . $e->getMessage();
}
```

### Error Collection

Use ErrorCollector to gather multiple errors:

```php
<?php

use Ardenexal\FHIRTools\Component\CodeGeneration\Service\ErrorCollector;

$errorCollector = new ErrorCollector();

try {
    $generator->generate('R4B');
} catch (GenerationException $e) {
    $errorCollector->addError($e->getMessage());
}

// Check for errors
if ($errorCollector->hasErrors()) {
    foreach ($errorCollector->getErrors() as $error) {
        echo "Error: {$error}\n";
    }
}

// Get error summary
$summary = $errorCollector->getSummary();
echo "Total errors: {$summary['total']}\n";
echo "Critical errors: {$summary['critical']}\n";
```

### Validation

Validate generated code:

```php
<?php

$context = new BuilderContext();
$context->setValidateGenerated(true);

$generator = new FHIRModelGenerator($context);

try {
    $generator->generate('R4B');
    echo "All generated code is valid\n";
} catch (GenerationException $e) {
    echo "Validation failed: " . $e->getMessage();
}
```

## Performance Optimization

### Package Caching

The PackageLoader automatically handles caching:

```php
<?php

use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;

$loader = new PackageLoader();

// Packages are automatically cached and reused
$metadata = $loader->installPackage('hl7.fhir.r4b.core', '4.3.0');

// Check cache statistics
$stats = $loader->getCacheStatistics();
echo "Cached packages for R4B: " . $stats['R4B']['package_count'] . "\n";
```

### Memory Management

For large packages, monitor memory usage:

```php
<?php

// Monitor memory usage during generation
$memoryBefore = memory_get_usage();
$generator->generate('R4B');
$memoryAfter = memory_get_usage();

echo "Memory used: " . ($memoryAfter - $memoryBefore) . " bytes\n";

// Force garbage collection if needed
gc_collect_cycles();
```

## Testing

### Unit Testing

```php
<?php

namespace Tests\CodeGeneration;

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use PHPUnit\Framework\TestCase;

class FHIRModelGeneratorTest extends TestCase
{
    private FHIRModelGenerator $generator;
    private BuilderContext $context;

    protected function setUp(): void
    {
        $this->context = new BuilderContext();
        $this->context->setOutputDirectory(sys_get_temp_dir() . '/fhir-test');
        $this->context->setBaseNamespace('Test\\FHIR');

        $this->generator = new FHIRModelGenerator($this->context);
    }

    public function testGeneratePatientClass(): void
    {
        $this->generator->generateResource('Patient');

        $outputFile = $this->context->getOutputDirectory() . '/FHIRPatient.php';
        self::assertFileExists($outputFile);

        $content = file_get_contents($outputFile);
        self::assertStringContainsString('class FHIRPatient', $content);
        self::assertStringContainsString('namespace Test\\FHIR', $content);
    }

    protected function tearDown(): void
    {
        // Clean up generated files
        $this->cleanupDirectory($this->context->getOutputDirectory());
    }

    private function cleanupDirectory(string $dir): void
    {
        if (is_dir($dir)) {
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                $path = $dir . '/' . $file;
                is_dir($path) ? $this->cleanupDirectory($path) : unlink($path);
            }
            rmdir($dir);
        }
    }
}
```

### Integration Testing

```php
<?php

namespace Tests\Integration;

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use PHPUnit\Framework\TestCase;

class GenerationIntegrationTest extends TestCase
{
    public function testFullGenerationWorkflow(): void
    {
        $context = new BuilderContext();
        $context->setOutputDirectory(sys_get_temp_dir() . '/fhir-integration-test');
        $context->setBaseNamespace('Integration\\FHIR');

        $generator = new FHIRModelGenerator($context);

        // Generate a small subset for testing
        $resources = ['Patient', 'Observation'];
        foreach ($resources as $resource) {
            $generator->generateResource($resource);
        }

        // Verify generated files
        foreach ($resources as $resource) {
            $file = $context->getOutputDirectory() . "/FHIR{$resource}.php";
            self::assertFileExists($file);

            // Verify the generated file is valid PHP
            $content = file_get_contents($file);
            self::assertStringContainsString("<?php", $content);
            self::assertStringContainsString("declare(strict_types=1);", $content);

            // Verify syntax by including the file
            $this->assertValidPhpSyntax($file);
        }
    }

    private function assertValidPhpSyntax(string $file): void
    {
        $output = [];
        $returnCode = 0;
        exec("php -l {$file}", $output, $returnCode);
        
        self::assertEquals(0, $returnCode, "Generated file has syntax errors: " . implode("\n", $output));
    }
}
```

## CLI Usage

### Command Line Interface

If using the component standalone, you can create CLI commands:

```php
#!/usr/bin/env php
<?php

require_once 'vendor/autoload.php';

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;

// Parse command line arguments
$options = getopt('v:o:n:', ['version:', 'output:', 'namespace:']);

$version = $options['v'] ?? $options['version'] ?? 'R4B';
$output = $options['o'] ?? $options['output'] ?? './generated';
$namespace = $options['n'] ?? $options['namespace'] ?? 'Generated\\FHIR';

// Create context
$context = new BuilderContext();
$context->setOutputDirectory($output);
$context->setBaseNamespace($namespace);

// Generate models
$generator = new FHIRModelGenerator($context);

echo "Generating FHIR {$version} models...\n";
echo "Output directory: {$output}\n";
echo "Base namespace: {$namespace}\n";

try {
    $generator->generate($version);
    echo "Generation completed successfully!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
```

Usage:
```bash
# Generate with defaults
php generate.php

# Generate specific version
php generate.php -v R5

# Generate with custom output
php generate.php -v R4B -o /path/to/output -n MyApp\\FHIR
```

## Best Practices

### Code Organization

1. **Separate Concerns**: Keep generation logic separate from business logic
2. **Use Interfaces**: Program against interfaces for better testability
3. **Error Handling**: Implement comprehensive error handling
4. **Logging**: Add logging for debugging and monitoring

### Performance

1. **Caching**: Enable package caching for repeated generations
2. **Parallel Processing**: Use parallel generation for large packages
3. **Memory Management**: Monitor memory usage with large datasets
4. **Incremental Generation**: Only regenerate changed resources

### Security

1. **Input Validation**: Validate all inputs and configuration
2. **File Permissions**: Set appropriate permissions on generated files
3. **Path Validation**: Prevent directory traversal attacks
4. **Error Messages**: Don't expose sensitive information in errors

### Maintenance

1. **Version Control**: Version control your generation configuration
2. **Documentation**: Document custom generators and templates
3. **Testing**: Test generated code thoroughly
4. **Monitoring**: Monitor generation performance and errors