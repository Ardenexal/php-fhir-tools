# PHP FHIR Tools

A PHP library monorepo for working with [FHIR](https://www.hl7.org/fhir/) (Fast Healthcare Interoperability Resources). This monorepo contains multiple standalone packages that can be used independently or together:

- **FHIR Code Generation**: Generate PHP model classes from FHIR Structure Definitions
- **FHIR Serialization**: JSON/XML serialization and deserialization
- **FHIRPath**: Expression evaluator for FHIRPath 2.0
- **FHIR Models**: Pre-generated FHIR model classes (R4, R4B, R5)
- **FHIR Bundle**: Symfony integration bundle

## Installation

### As a Library (Recommended)

Install individual components in your project:

```bash
# For Symfony applications - includes console commands
composer require ardenexal/fhir-bundle

# For code generation
composer require ardenexal/fhir-code-generation

# For serialization
composer require ardenexal/fhir-serialization

# For FHIRPath evaluation
composer require ardenexal/fhir-path

# For pre-generated models
composer require ardenexal/fhir-models
```

### For Development

Clone and develop on this monorepo:

```bash
git clone https://github.com/Ardenexal/php-fhir-tools.git
cd php-fhir-tools
composer install
```

## Quick Start

### Using FHIR Bundle in Symfony

If you've installed `ardenexal/fhir-bundle` in your Symfony application:

```bash
# Generate FHIR models
php bin/console fhir:generate --package=hl7.fhir.r4.core -vvv

# Evaluate FHIRPath expressions
php bin/console fhir:path:evaluate "5 + 3"

# Against a JSON file
php bin/console fhir:path:evaluate "Patient.name.given" patient.json

# Output as JSON
php bin/console fhir:path:evaluate "name" patient.json --format=json --pretty
```

### Serialize FHIR Resources

```php
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;

// Serialize to JSON
$json = $service->serializeToJson($patient);

// Deserialize from JSON
$patient = $service->deserializeFromJson($json, FHIRPatient::class);

// Auto-detect format (JSON or XML)
$resource = $service->deserialize($data);
```

## Project Structure

```
src/
├── Bundle/FHIRBundle/              # Symfony Bundle integration and console commands
├── Component/
│   ├── CodeGeneration/src/         # FHIR model class generation from Structure Definitions
│   ├── Serialization/src/          # FHIR JSON/XML serialization and deserialization
│   ├── Models/src/                 # Generated FHIR model classes (R4, R4B, R5)
│   └── FHIRPath/src/               # FHIRPath 2.0 expression evaluator
```

Each component has its own README with detailed documentation:

- [FHIRBundle](src/Bundle/FHIRBundle/README.md) — Symfony integration and console commands
- [CodeGeneration](src/Component/CodeGeneration/README.md) — Model generation from FHIR packages
- [Serialization](src/Component/Serialization/README.md) — JSON/XML serialization
- [FHIRPath](src/Component/FHIRPath/README.md) — FHIRPath expression evaluation
- [Models](src/Component/Models/README.md) — Generated FHIR model classes

## Monorepo Structure

This is a library monorepo. Each component can be installed and used independently:

| Package | Description | Composer Install |
|---------|-------------|------------------|
| `ardenexal/fhir-bundle` | Symfony Bundle with console commands | `composer require ardenexal/fhir-bundle` |
| `ardenexal/fhir-code-generation` | Generate PHP from FHIR definitions | `composer require ardenexal/fhir-code-generation` |
| `ardenexal/fhir-serialization` | JSON/XML serialization | `composer require ardenexal/fhir-serialization` |
| `ardenexal/fhir-path` | FHIRPath expression evaluator | `composer require ardenexal/fhir-path` |
| `ardenexal/fhir-models` | Pre-generated FHIR models | `composer require ardenexal/fhir-models` |

## Development

### Code Quality

```bash
# Fix code style (PSR-12 via Laravel Pint)
composer lint

# Static analysis (PHPStan level 8)
composer phpstan

# Run all tests
composer test

# Run specific test suites
composer test-unit
composer test-integration
composer test-fhir

# Full quality check (lint + phpstan + test)
composer quality:all
```

### Component-Specific Quality Checks

```bash
composer quality:bundle
composer quality:codegen
composer quality:serialization
composer quality:fhir-path
```

## Console Commands (via FHIR Bundle)

When you install `ardenexal/fhir-bundle` in your Symfony application, you get these console commands:

| Command | Description |
|---------|-------------|
| `fhir:generate` | Generate PHP model classes from FHIR packages |
| `fhir:path:evaluate` | Evaluate a FHIRPath expression against FHIR data |
| `fhir:path:validate` | Validate FHIRPath expression syntax |

**Note**: These commands are only available when using the FHIR Bundle in a Symfony application. This monorepo is a library, not an application.

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for development setup, coding standards, and contribution guidelines.

## License

This project is licensed under the MIT License — see the [LICENSE](LICENSE) file for details.
