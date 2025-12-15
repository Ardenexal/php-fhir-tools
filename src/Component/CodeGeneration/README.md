# FHIR Code Generation Component

Standalone PHP library for generating FHIR model classes from FHIR StructureDefinitions.

## Installation

```bash
composer require ardenexal/fhir-code-generation
```

## Usage

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\FHIRModelGenerator;

$generator = new FHIRModelGenerator(
    outputDirectory: '/path/to/output',
    baseNamespace: 'App\\FHIR',
    generateTests: false
);

$generator->generateFromPackage('hl7.fhir.r4.core');
```

## Features

- Generate PHP classes from FHIR StructureDefinitions
- Support for FHIR R4, R4B, and R5
- Package management and dependency resolution
- Semantic version support
- Comprehensive error handling

## Requirements

- PHP 8.3+
- nette/php-generator ^4.2.0
- composer/semver ^3.4.4