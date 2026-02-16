# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with this repository.

## Project Overview

**PHP FHIR Tools** is a multi-project toolkit for working with FHIR (Fast Healthcare Interoperability Resources) in PHP applications. It generates PHP model classes and enums from FHIR Structure Definitions using Symfony Console and Nette PhpGenerator, and provides FHIR serialization for JSON/XML formats.

## Architecture

```
src/
├── Bundle/FHIRBundle/              # Symfony Bundle integration
├── Component/
│   ├── CodeGeneration/src/         # FHIR class generation
│   ├── Serialization/src/          # FHIR JSON/XML serialization
│   ├── Models/src/                 # Generated FHIR models
│   └── FHIRPath/src/               # FHIRPath expression evaluator
├── Exception/                      # Project-wide exceptions
└── Serialization/                  # Legacy serialization (use Component)
```

**Namespace pattern:** `Ardenexal\FHIRTools\Component\{ComponentName}\`

## Development Commands

```bash
# Install dependencies
composer install

# Generate FHIR models (R4, R4B, R5)
composer run generate-models-all

# Generate specific version
composer run generate-models-r4
composer run generate-models-r4b
composer run generate-models-r5

# Run all tests
composer test

# Run specific test suites
composer test-unit
composer test-integration
composer test-fhir

# Component-specific tests
composer test:bundle
composer test:codegen
composer test:serialization

# Code quality
composer lint              # Fix code style with Pint
composer phpstan           # Static analysis (level 8)

# Full quality check
composer quality:all       # lint + phpstan + test
```

## Key Files

- `bin/console` - Symfony Console entry point
- `composer.json` - Dependencies and scripts
- `phpstan.neon` - PHPStan configuration (level 8)
- `config/services.yaml` - Symfony service definitions
- `resources/definitions/` - FHIR definition files

## Coding Standards

- **PHP 8.3+** required
- **Strict types**: Always use `declare(strict_types=1);`
- **PSR-12**: Run `composer lint` to fix code style
- **PHPStan level 8**: Run `composer phpstan` before committing
- **Symfony best practices**: Use dependency injection, avoid `new` in commands
- **PHPUnit 11+/12+**: Use `self::assert*` methods and `void` return types on tests

## Testing Guidelines

- Tests located in `tests/` directory
- Use `Ardenexal\FHIRTools\Tests\` namespace
- Test fixtures in `tests/Fixtures/`
- Integration tests verify FHIR conformance and serialization round-trips

## FHIR Model Generation

The `fhir:generate` command generates PHP classes from FHIR packages:

```bash
php bin/console fhir:generate --models-component --package=hl7.fhir.r4.core -vvv
```

Generated models go to `src/Component/Models/src/`.

## Serialization

Use the Serialization component for FHIR JSON/XML handling:

```php
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationContext;

$context = FHIRSerializationContext::forJson()
    ->withValidationMode(FHIRSerializationContext::VALIDATION_STRICT);
```

## Commit Guidelines

- Use conventional commits: `feat:`, `fix:`, `chore:`, `test:`
- No AI mentions in commits or PRs
- Sign commits with GPG when possible
