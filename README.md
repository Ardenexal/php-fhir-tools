# PHP FHIR Tools

This project generates PHP model classes and enums from FHIR Structure Definitions using Symfony Console and Nette PhpGenerator. It also provides a comprehensive FHIR serialization system for converting between FHIR objects and JSON/XML formats.

## Requirements
- PHP >= 8.2
- Composer

## Installation
Clone the repository and install dependencies:
```bash
composer install
```

## Usage
Run the Symfony Console application:
```bash
php bin/console
```

### Available Commands

#### `fhir:generate`
Generates FHIR model classes from FHIR definitions.

**Arguments:**
- `version` (required): Select FHIR version to generate model classes for.
  - Suggested values: `R4`, `R4B`, `R5`

**Example:**
```bash
php bin/console fhir:generate R4B
```

## FHIR Serialization

This project includes a comprehensive FHIR serialization system that provides:

- **JSON and XML serialization** following official FHIR specifications
- **Configurable validation modes** (strict/lenient)
- **Flexible unknown element handling** (ignore/error/preserve)
- **Performance optimization options**
- **Debug information support**
- **Extension handling** with proper FHIR formatting

### Quick Example

```php
use Symfony\Component\Serializer\Serializer;
use Ardenexal\FHIRTools\Serialization\FHIRResourceNormalizer;
use Ardenexal\FHIRTools\Serialization\FHIRSerializationContext;

// Set up FHIR-aware serializer
$normalizers = [new FHIRResourceNormalizer($metadataExtractor, $typeResolver)];
$serializer = new Serializer($normalizers);

// Configure serialization context
$context = FHIRSerializationContext::forJson()
    ->withValidationMode(FHIRSerializationContext::VALIDATION_STRICT);

// Serialize FHIR resource
$json = $serializer->serialize($patient, 'json', $context->toSymfonyContext());

// Deserialize back to FHIR object
$patient = $serializer->deserialize($json, FHIRPatient::class, 'json', $context->toSymfonyContext());
```

For comprehensive documentation on the serialization system, see: **[FHIR Serialization Guide](docs/FHIR-Serialization-Guide.md)**

## Documentation

- **[FHIR Serialization Guide](docs/FHIR-Serialization-Guide.md)** - Comprehensive guide for using the FHIR serialization system

## Composer Scripts
- Run static analysis:
  ```bash
  composer phpstan
  ```
- Run code style linter:
  ```bash
  composer lint
  ```
- Run tests:
  ```bash
  composer test
  ```
- Generate FHIR models:
  ```bash
  composer run generate-models-all
  ```

## GitHub Actions

### Regenerate FHIR Models
This repository includes a GitHub Actions workflow that allows you to manually regenerate FHIR models and commit them to the main branch.

**To trigger the workflow:**
1. Go to the "Actions" tab in the GitHub repository
2. Select "Regenerate FHIR Models" from the workflows list
3. Click "Run workflow"
4. Optionally customize the commit message (default: "chore: regenerate FHIR models")
5. Click the green "Run workflow" button

The workflow will:
- Set up PHP 8.3 environment with required extensions
- Install Composer dependencies
- Run `composer run generate-models-all` to generate models for R4, R4B, and R5
- Automatically commit and push changes to main if models were updated
- Provide a summary of the operation

## Project Structure
- `src/` - Source code
- `bin/console` - Symfony Console entry point
- `resources/definitions/` - FHIR definition files

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

