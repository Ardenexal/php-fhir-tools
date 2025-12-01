# PHP FHIR Tools

This project generates PHP model classes and enums from FHIR Structure Definitions using Symfony Console and Nette PhpGenerator.

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

## Composer Scripts
- Run static analysis:
  ```bash
  composer phpstan
  ```
- Run code style linter:
  ```bash
  composer lint
  ```

## Project Structure
- `src/` - Source code
- `bin/console` - Symfony Console entry point
- `resources/definitions/` - FHIR definition files

## License
Proprietary

