# FHIR Code Generation Component

PHP library for generating FHIR model classes from StructureDefinitions. Produces PHP classes, data types, primitives, and enums from FHIR Implementation Guide packages. Also generates typed extension and profile classes for specific Implementation Guides.

## Features

- Generate PHP classes from FHIR StructureDefinitions
- Generate PHP enums from FHIR ValueSets
- Generate typed extension and profile classes for FHIR Implementation Guides
- Package management with FHIR registry integration
- Semantic version resolution and dependency handling
- Local package caching with integrity verification
- Offline mode for air-gapped environments

## How It Works

### Base FHIR Model Generation

The `fhir:generate` pipeline:

1. **Package Loading** — Downloads and caches FHIR IG packages (`.tgz`) from `packages.fhir.org`
2. **Definition Parsing** — Reads StructureDefinition and ValueSet JSON resources from the package
3. **Class Generation** — Produces PHP classes using [Nette PhpGenerator](https://github.com/nette/php-generator):
   - Resources (Patient, Observation, etc.)
   - Data Types (HumanName, Address, etc.)
   - Primitives (FHIRString, FHIRInteger, etc.)
   - Backbone Elements (nested within parent resource namespaces)
4. **Enum Generation** — Produces PHP enums from required ValueSets (AdministrativeGender, etc.)

### Implementation Guide Generation

The `fhir:generate-ig` pipeline generates typed classes for IG-specific StructureDefinitions (those with `derivation: constraint`) that are intentionally skipped by `fhir:generate`:

- **Named extensions** (`type: Extension`, `derivation: constraint`) — each becomes a typed subclass of `Extension` with the URL baked in and value or sub-extension properties narrowed to the concrete types the IG defines.
- **Resource/type profiles** (`kind: resource/complex-type`, `derivation: constraint`) — each becomes a thin subclass of its base resource or type, carrying a `PROFILE_URL` constant and a `#[FHIRProfile]` attribute.

Multi-level inheritance chains are supported: `hl7.fhir.au.core` profiles extend `hl7.fhir.au.base` profiles which extend base FHIR R4 resources. Specify packages in dependency order and the generator handles the rest.

Output is written to an isolated `IG/{version}/{slug}/` namespace tree that never overlaps with the base models.

## Usage

### Base FHIR models (`fhir:generate`)

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

### Implementation Guide classes (`fhir:generate-ig`)

```bash
# Single IG
php bin/console fhir:generate-ig --package=hl7.fhir.us.core

# Pin a version
php bin/console fhir:generate-ig --package=hl7.fhir.us.core#6.1.0

# Multi-level chain — specify in dependency order
php bin/console fhir:generate-ig --package=hl7.fhir.au.base#1.0.0 --package=hl7.fhir.au.core#1.0.0

# Offline mode
php bin/console fhir:generate-ig --package=hl7.fhir.us.core --offline
```

When using the [FHIR Bundle](../../Bundle/FHIRBundle/README.md) in a Symfony application, the packages can be configured in `config/packages/fhir.yaml` so no CLI arguments are needed at runtime — see the bundle documentation for details.

### Output structure

```
Models/src/
├── R4/               ← canonical base types (fhir:generate)
└── IG/
    └── R4/
        ├── UsCore/
        │   ├── Extension/   ← typed extension classes
        │   └── Profile/     ← resource profile subclasses
        └── AuCore/
            ├── Extension/
            └── Profile/
```

### Generated class examples

**Simple extension** (`patient-birthPlace` → `PatientBirthPlaceExtension`):

```php
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-birthPlace', fhirVersion: 'R4')]
class PatientBirthPlaceExtension extends Extension
{
    public function __construct(
        public ?Address $valueAddress = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(url: 'http://hl7.org/fhir/StructureDefinition/patient-birthPlace', value: $this->valueAddress);
    }
}
```

**Resource profile** (`AUCorePatient` extending `AUBasePatientProfile`):

```php
#[FHIRProfile(profileUrl: 'http://hl7.org.au/fhir/core/StructureDefinition/au-core-patient', baseType: 'Patient', fhirVersion: 'R4')]
class AUCorePatientProfile extends AUBasePatientProfile
{
    public const string PROFILE_URL = 'http://hl7.org.au/fhir/core/StructureDefinition/au-core-patient';
}
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

### FHIRExtensionGenerator

Generates typed PHP classes for named FHIR extensions (`type: Extension`, `derivation: constraint`). Detects simple vs. complex extensions automatically:

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRExtensionGenerator;

$generator = new FHIRExtensionGenerator();
$class = $generator->generate($structureDefinition, 'R4', $builderContext, $namespace);
```

### FHIRProfileGenerator

Generates profile subclasses for resource/type profiles (`derivation: constraint`, `kind: resource/complex-type`). Resolves parent classes from the `BuilderContext`, enabling multi-level inheritance chains:

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRProfileGenerator;

$generator = new FHIRProfileGenerator();
$class = $generator->generate($structureDefinition, 'R4', $builderContext, $namespace);
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
