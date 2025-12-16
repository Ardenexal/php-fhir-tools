# FHIR Tools - Code Generation Component

This component provides FHIR code generation functionality for PHP FHIRTools. It handles the generation of PHP classes and enums from FHIR StructureDefinitions, ValueSets, and CodeSystems.

## Features

- **Modular Architecture**: Clean separation of concerns with well-defined interfaces
- **Minimal Dependencies**: Only essential dependencies for code generation
- **Extensible Design**: Plugin-based generator system for different FHIR resource types
- **Error Handling**: Comprehensive error collection and reporting
- **Package Management**: Support for loading and managing FHIR packages

## Core Interfaces

### Generator System
- `GeneratorInterface`: Contract for FHIR code generators
- `CodeGenerationServiceInterface`: Main service for orchestrating code generation
- `GenerationResultInterface`: Results and statistics from generation operations

### Context Management
- `BuilderContextInterface`: Manages generated types, namespaces, and definitions
- `GenerationConfigurationInterface`: Configuration for generation options

### Package Management
- `PackageLoaderInterface`: Loading FHIR packages from various sources
- `PackageInterface`: Represents a loaded FHIR package

## Exception Hierarchy

- `GenerationException`: Base exception for code generation errors
- `PackageException`: Exceptions related to package operations

## Usage

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Service\CodeGenerationService;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;

$context = new BuilderContext();
$service = new CodeGenerationService($context);

$result = $service->generateFromPackage($package, '/output/directory');

if ($result->isSuccessful()) {
    echo "Generated {$result->getClassCount()} classes and {$result->getEnumCount()} enums";
} else {
    foreach ($result->getErrors() as $error) {
        echo "Error: {$error}\n";
    }
}
```

## Requirements

- PHP 8.2+
- Nette PHP Generator 4.1+
- Symfony String Component 6.4+|7.0+
- Symfony Intl Component 6.4+|7.0+
- Symfony Validator Component 6.4+|7.0+

## License

MIT License - see LICENSE file for details.
