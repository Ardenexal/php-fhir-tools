# FHIR Code Generation Guidelines

## Component Architecture

### CodeGeneration Component
Located at `src/Component/CodeGeneration/src/`:
- **Command**: `FHIRModelGeneratorCommand` - Symfony console command
- **Generator**: `FHIRModelGenerator` - Core generation logic
- **ValueSet Generator**: `FHIRValueSetGenerator` - Enum generation
- **Package Loader**: `PackageLoader` - FHIR package management
- **Error Collector**: `ErrorCollector` - Validation error accumulation

### Code Generation Library
Uses **Nette PhpGenerator** for PHP class generation (not templates).

## FHIR Attributes

Generated classes use PHP 8 attributes for FHIR metadata:
```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
```

## Generation Commands

```bash
# Generate all FHIR versions
composer run generate-models-all

# Generate specific versions
composer run generate-models-r4
composer run generate-models-r4b
composer run generate-models-r5

# Manual generation with options
php bin/console fhir:generate --models-component --package=hl7.fhir.r4.core -vvv
```

## Generated Output Structure

```
src/Component/Models/src/
├── R4/
│   ├── Resource/           # FHIR Resources (Patient, Observation, etc.)
│   ├── Complex/            # Complex types
│   ├── Primitive/          # Primitive types
│   └── ValueSet/           # Enums from ValueSets
├── R4B/
│   └── ...
└── R5/
    └── ...
```

## Generated Class Patterns

### Resources and Complex Types
- Use promoted constructor properties
- Include PHPDoc with FHIR element documentation
- Implement serialization support via attributes

### ValueSet Enums
- Generated as PHP 8.1+ enums
- Include PHPDoc with ValueSet URI and description
- String-backed enums matching FHIR codes

## Key Services

### BuilderContext
Provides context for generation including:
- Output paths
- Namespace configuration
- FHIR version information

### ErrorCollector
Accumulates validation errors during generation:
- Allows batch reporting of all errors
- Distinguishes warnings from errors

### PackageLoader
Handles FHIR package management:
- Downloads from FHIR package registry
- Caches packages locally
- Resolves package dependencies

### RetryHandler
Implements retry logic for network operations:
- Exponential backoff
- Configurable max attempts

## Testing Generated Code

```bash
# Test code generation component
composer test:codegen

# Validate generated models work with serialization
composer test:serialization
```
