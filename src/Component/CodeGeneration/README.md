# FHIR Code Generation Component

PHP library for generating FHIR model classes from StructureDefinitions. Produces PHP classes, data types, primitives, and enums from FHIR Implementation Guide packages.

## Features

- Generate PHP classes from FHIR StructureDefinitions
- Generate PHP enums from FHIR ValueSets
- Package management with FHIR registry integration
- Semantic version resolution and dependency handling
- Local package caching with integrity verification
- Offline mode for air-gapped environments

## How It Works

The code generation pipeline:

1. **Package Loading** — Downloads and caches FHIR IG packages (`.tgz`) from `packages.fhir.org`
2. **Definition Parsing** — Reads StructureDefinition and ValueSet JSON resources from the package
3. **Class Generation** — Produces PHP classes using [Nette PhpGenerator](https://github.com/nette/php-generator):
   - Resources (Patient, Observation, etc.)
   - Data Types (HumanName, Address, etc.)
   - Primitives (FHIRString, FHIRInteger, etc.)
   - Backbone Elements (nested within parent resource namespaces)
4. **Enum Generation** — Produces PHP enums from required ValueSets (AdministrativeGender, etc.)

## Usage

Generation is driven through the `fhir:generate` console command:

```bash
# Generate R4 models
php bin/console fhir:generate --package=hl7.fhir.r4.core -vvv

# Generate R4B models
php bin/console fhir:generate --package=hl7.fhir.r4b.core -vvv

# Generate multiple versions at once
php bin/console fhir:generate --package=hl7.fhir.r4.core --package=hl7.fhir.r4b.core -vvv

# Offline mode (cached packages only)
php bin/console fhir:generate --package=hl7.fhir.r4.core --offline -vvv
```

The terminology package (`hl7.terminology.r4`) is automatically included as a dependency.

### Composer Scripts

```bash
composer run generate-models-r4      # R4 only
composer run generate-models-r4b     # R4B only
composer run generate-models-all     # R4 + R4B + R5
```

## Core Classes

### FHIRModelGenerator

Generates PHP classes from individual StructureDefinitions:

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRModelGenerator;

// Called internally by the generate command for each StructureDefinition
$classType = $generator->generate($structureDefinition, $version, $builderContext);
```

### FHIRValueSetGenerator

Generates PHP enums from ValueSet definitions:

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRValueSetGenerator;

$enumType = $generator->generateEnum($valueSetDefinition, $version, $builderContext);
```

### PackageLoader

Manages FHIR package downloads, caching, and extraction:

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;

// Install a package (downloads if not cached)
$metadata = $loader->installPackage('hl7.fhir.r4.core', '4.0.1');

// Load StructureDefinitions into the build context
$definitions = $loader->loadPackageStructureDefinitions($metadata);

// Check cache
$isCached = $loader->isPackageCached('hl7.fhir.r4.core', '4.0.1');
```

### BuilderContext

Holds state during generation — loaded definitions, generated classes, namespace registrations:

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;

$context = new BuilderContext();
$context->addDefinition($url, $definition);
```

## Error Handling

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException;

try {
    $metadata = $loader->installPackage('hl7.fhir.r4.core');
} catch (PackageException $e) {
    // Package download, extraction, or integrity failures
} catch (GenerationException $e) {
    // Code generation failures
}
```

Non-fatal errors are collected via `ErrorCollector` and reported at the end of generation.

## Requirements

- **PHP**: 8.3 or higher

## License

This component is released under the MIT License. See the [LICENSE](../../../LICENSE) file for details.
