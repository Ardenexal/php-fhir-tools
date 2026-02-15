# Technology Stack

## Core Technologies

- **PHP**: 8.3+ (strict types required)
- **Symfony Framework**: 6.4 | 7.4 (multi-version support)
- **Composer**: Package management and autoloading

## Key Dependencies

### Code Generation
- **Nette PHP Generator**: 4.2+ for generating PHP classes
- **Composer Semver**: 3.4+ for version handling
- **Amphp HTTP Client**: 5.3+ for async HTTP operations

### Serialization & Validation
- **Symfony Serializer**: JSON/XML serialization
- **Symfony Validator**: FHIR validation constraints
- **Symfony Property Access**: Dynamic property handling

### Development Tools
- **PHPUnit**: 11+ | 12.5+ for testing
- **PHPStan**: Level 8 static analysis
- **Laravel Pint**: PSR-12 code formatting
- **Giorgiosironi Eris**: Property-based testing

## Build System

### Quality Assurance Commands
```bash
# Code style and formatting
composer run lint                    # Fix PSR-12 violations
composer run lint:bundle            # Lint FHIRBundle only
composer run lint:codegen           # Lint CodeGeneration component
composer run lint:serialization     # Lint Serialization component

# Static analysis
composer run phpstan                 # Full PHPStan analysis (level 8)
composer run phpstan:bundle         # Analyze FHIRBundle only
composer run phpstan:codegen        # Analyze CodeGeneration component
composer run phpstan:serialization  # Analyze Serialization component
composer run phpstan:fhir-path      # Analyze FHIRPath component

# Testing
composer run test                    # Run all tests
composer run test-unit              # Unit tests only
composer run test-integration       # Integration tests only
composer run test-coverage          # Generate coverage report
composer run test:bundle            # Test FHIRBundle only
composer run test:codegen           # Test CodeGeneration component
composer run test:serialization     # Test Serialization component
composer run test:fhir-path         # Test FHIRPath component
composer run test:components        # Test all components

# Combined quality checks
composer run quality:all            # Lint + PHPStan + Test (full)
composer run quality:bundle         # Quality check FHIRBundle
composer run quality:codegen        # Quality check CodeGeneration
composer run quality:serialization  # Quality check Serialization
composer run quality:fhir-path      # Quality check FHIRPath
composer run quality:components     # Quality check all components
```

### FHIR Model Generation
```bash
# Generate FHIR models
composer run generate-models-all     # Generate R4, R4B, R5 models
composer run generate-models-r4      # Generate R4 models only
composer run generate-models-r4b     # Generate R4B models only
composer run generate-models-r5      # Generate R5 models only
```

### Console Commands
```bash
# Main FHIR generation command
php bin/console fhir:generate --models-component --package=hl7.fhir.r4.core

# FHIRPath evaluation
php bin/console fhir:path:evaluate <expression> <data>
php bin/console fhir:path:validate <expression>
```

## Project Structure Standards

### Namespace Organization
- **Root**: `Ardenexal\FHIRTools\`
- **Components**: `Ardenexal\FHIRTools\Component\{ComponentName}\`
- **Bundle**: `Ardenexal\FHIRTools\Bundle\FHIRBundle\`
- **Generated Models**: Configurable base namespace

### PSR Standards
- **PSR-4**: Autoloading standard
- **PSR-12**: Code style standard (enforced by Pint)
- **Strict Types**: Required in all PHP files

### Testing Framework
- **PHPUnit 12+**: Modern testing framework
- **Test Structure**: Unit, Integration, FHIR-specific test suites
- **Property Testing**: Eris for property-based testing
- **Coverage**: HTML and Clover reports

## Development Environment

### Required PHP Extensions
- `ext-ctype`: Character type checking
- `ext-iconv`: Character encoding conversion
- `ext-zip`: ZIP archive handling

### Configuration Files
- `phpunit.dist.xml`: PHPUnit configuration
- `phpstan.neon`: Static analysis configuration
- `pint.json`: Code style configuration
- `.env`: Environment variables

### CI/CD Integration
- GitHub Actions workflows for automated testing
- Automated FHIR model regeneration
- Multi-version PHP testing support
