# FHIR Code Generation Component

Standalone PHP library for generating FHIR model classes from StructureDefinitions. This component can be used independently or as part of the FHIRBundle in Symfony applications.

## Features

- **Standalone Library**: Use independently without Symfony
- **FHIR Model Generation**: Generate PHP classes from FHIR StructureDefinitions
- **ValueSet Enums**: Generate PHP enums from FHIR ValueSets
- **Package Management**: Load and manage FHIR packages from various sources
- **Minimal Dependencies**: Only essential dependencies for code generation
- **Extensible Design**: Plugin-based generator system
- **Error Handling**: Comprehensive error collection and reporting
- **Performance Optimized**: Parallel generation and caching support

## Installation

### Standalone Installation

```bash
composer require ardenexal/fhir-code-generation
```

### With FHIRBundle

```bash
composer require ardenexal/fhir-bundle
```

## Quick Start

### Basic Usage

```php
<?php

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;

// Create context
$context = new BuilderContext();
$context->setOutputDirectory('/path/to/output');
$context->setBaseNamespace('MyApp\\FHIR');

// Create generator
$generator = new FHIRModelGenerator($context);

// Generate FHIR models
$generator->generate('R4B');
```

### Advanced Configuration

```php
<?php

// Create context with proper namespaces
$context = new BuilderContext();
$elementNamespace = new PhpNamespace('MyApp\\FHIR\\Element');
$enumNamespace = new PhpNamespace('MyApp\\FHIR\\Enum');
$context->addElementNamespace('R4B', $elementNamespace);
$context->addEnumNamespace('R4B', $enumNamespace);

// Create generator
$generator = new FHIRModelGenerator($context);

// Generate specific resources
$generator->generateResource('Patient');
$generator->generateResource('Observation');
```

## Core Components

### FHIRModelGenerator

Main class for generating FHIR model classes:

```php
<?php

$generator = new FHIRModelGenerator($context);

// Generate all models for a FHIR version
$generator->generate('R4B');

// Generate specific resources
$generator->generateResource('Patient');
$generator->generateResource('Observation');
```

### FHIRValueSetGenerator

Generate PHP enums from FHIR ValueSets:

```php
<?php

$generator = new FHIRValueSetGenerator($context);

// Generate specific value sets
$generator->generateValueSet('administrative-gender');
$generator->generateValueSet('observation-status');
```

### PackageLoader

Load and manage FHIR packages:

```php
<?php

use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;

$loader = new PackageLoader();

// Load package (actual method from implementation)
$metadata = $loader->installPackage('hl7.fhir.r4b.core', '4.3.0');

// Load to context
$loader->loadPackageToContext($metadata, 'R4B');
```

## Configuration Options

### BuilderContext Configuration

```php
<?php

$context = new BuilderContext();

// Note: BuilderContext is primarily for managing generated types and namespaces
// Most configuration is handled through the generator classes themselves

// The BuilderContext manages:
// - Generated classes and enums
// - FHIR definitions  
// - Namespace organization
// - Pending type resolution

// Add namespaces for a FHIR version
$elementNamespace = new PhpNamespace('MyApp\\FHIR\\Element');
$enumNamespace = new PhpNamespace('MyApp\\FHIR\\Enum');
$context->addElementNamespace('R4B', $elementNamespace);
$context->addEnumNamespace('R4B', $enumNamespace);
```

## Advanced Usage

### Custom Generators

```php
<?php

namespace MyApp\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;

class CustomFHIRGenerator extends FHIRModelGenerator
{
    protected function generateClass(array $structureDefinition): string
    {
        $code = parent::generateClass($structureDefinition);
        
        // Add custom methods
        $code .= $this->generateCustomMethods($structureDefinition);
        
        return $code;
    }

    private function generateCustomMethods(array $structureDefinition): string
    {
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

```php
<?php

class BatchGenerator
{
    public function generateMultipleVersions(array $versions): void
    {
        foreach ($versions as $version) {
            $this->generator->getContext()->setOutputDirectory("/output/{$version}");
            $this->generator->getContext()->setBaseNamespace("FHIR\\{$version}");
            
            $this->generator->generate($version);
        }
    }
}

$batchGenerator = new BatchGenerator($generator);
$batchGenerator->generateMultipleVersions(['R4', 'R4B', 'R5']);
```

## Error Handling

### Exception Types

```php
<?php

use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException;

try {
    $generator->generate('R4B');
} catch (GenerationException $e) {
    echo "Generation error: " . $e->getMessage();
} catch (PackageException $e) {
    echo "Package error: " . $e->getMessage();
}
```

### Error Collection

```php
<?php

use Ardenexal\FHIRTools\Component\CodeGeneration\Service\ErrorCollector;

$errorCollector = new ErrorCollector();

try {
    $generator->generate('R4B');
} catch (GenerationException $e) {
    $errorCollector->addError($e->getMessage());
}

if ($errorCollector->hasErrors()) {
    foreach ($errorCollector->getErrors() as $error) {
        echo "Error: {$error}\n";
    }
}
```

## Performance Considerations

### Package Caching

The PackageLoader automatically caches downloaded packages:

```php
<?php

$loader = new PackageLoader();

// Packages are automatically cached and reused
$metadata = $loader->installPackage('hl7.fhir.r4b.core', '4.3.0');

// Check cache statistics
$stats = $loader->getCacheStatistics();
echo "Cached packages: " . $stats['R4B']['package_count'] . "\n";
```

### Memory Management

For large FHIR packages, monitor memory usage:

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

### Batch Processing

Process multiple resources efficiently:

```php
<?php

$resources = ['Patient', 'Observation', 'Practitioner'];
foreach ($resources as $resource) {
    $generator->generateResource($resource);
    
    // Optional: Force garbage collection between resources
    if (memory_get_usage() > 100 * 1024 * 1024) { // 100MB
        gc_collect_cycles();
    }
}
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
    public function testGeneratePatientClass(): void
    {
        $context = new BuilderContext();
        $context->setOutputDirectory(sys_get_temp_dir() . '/fhir-test');
        $context->setBaseNamespace('Test\\FHIR');

        $generator = new FHIRModelGenerator($context);
        $generator->generateResource('Patient');

        $outputFile = $context->getOutputDirectory() . '/FHIRPatient.php';
        self::assertFileExists($outputFile);

        $content = file_get_contents($outputFile);
        self::assertStringContainsString('class FHIRPatient', $content);
    }
}
```

## CLI Usage

### Command Line Interface

```php
#!/usr/bin/env php
<?php

require_once 'vendor/autoload.php';

use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;

$options = getopt('v:o:n:', ['version:', 'output:', 'namespace:']);

$version = $options['v'] ?? $options['version'] ?? 'R4B';
$output = $options['o'] ?? $options['output'] ?? './generated';
$namespace = $options['n'] ?? $options['namespace'] ?? 'Generated\\FHIR';

$context = new BuilderContext();
$context->setOutputDirectory($output);
$context->setBaseNamespace($namespace);

$generator = new FHIRModelGenerator($context);

echo "Generating FHIR {$version} models...\n";
$generator->generate($version);
echo "Generation completed!\n";
```

Usage:
```bash
php generate.php -v R4B -o /path/to/output -n MyApp\\FHIR
```

## Core Interfaces

### Generator System
- `GeneratorInterface`: Contract for FHIR code generators
- `CodeGenerationServiceInterface`: Main service interface
- `GenerationResultInterface`: Results from generation operations

### Context Management
- `BuilderContextInterface`: Manages generation context
- `GenerationConfigurationInterface`: Configuration interface

### Package Management
- `PackageLoaderInterface`: Loading FHIR packages
- `PackageInterface`: Represents a FHIR package

## Requirements

- **PHP**: 8.2 or higher
- **Dependencies**:
  - `nette/php-generator`: ^4.2.0
  - `composer/semver`: ^3.4.4

## Documentation

For detailed documentation, see:

- **Component Guide**: `/docs/component-guides/code-generation.md`
- **Architecture Overview**: `/docs/architecture.md`
- **Migration Guide**: `/docs/migration-guide.md`

## License

This component is released under the MIT License. See the LICENSE file for details.
