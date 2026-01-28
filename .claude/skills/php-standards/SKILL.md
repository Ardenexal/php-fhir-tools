# PHP FHIRTools Development Standards

## Core Requirements

### PHP Standards
- **PHP Version**: 8.3+ required (per composer.json)
- **Strict Types**: Always include `declare(strict_types=1);` at the top of every PHP file
- **PSR-12 Compliance**: All code must follow PSR-12 coding standards
- **Type Hints**: Use strict type hints for all parameters and return types

### Code Quality Tools
- **Pint**: Run `composer lint` to fix code style (uses Symfony preset with PSR-12)
- **PHPStan**: Run `composer phpstan` for static analysis (level 8)
- **PHPUnit**: Use PHPUnit 11+/12+, use `void` return types on tests, use `self::assert*`

### Component Structure
```
src/
├── Bundle/FHIRBundle/              # Symfony Bundle integration
├── Component/
│   ├── CodeGeneration/src/         # FHIR class generation (uses Nette PhpGenerator)
│   ├── Serialization/src/          # FHIR JSON/XML serialization
│   ├── Models/src/                 # Generated FHIR models (R4, R4B, R5)
│   └── FHIRPath/src/               # FHIRPath expression evaluator
├── Exception/                      # Project-wide exceptions
└── Serialization/                  # Legacy serialization (deprecated)
```

### Namespace Organization
- **Component Namespace**: `Ardenexal\FHIRTools\Component\{ComponentName}\`
- **Bundle Namespace**: `Ardenexal\FHIRTools\Bundle\FHIRBundle\`
- **Tests Namespace**: `Ardenexal\FHIRTools\Tests\`

### Symfony Framework Standards
- **Dependency Injection**: Use Symfony's DI container, avoid `new` keyword in commands
- **Console Components**: Use `#[AsCommand]` attribute for command configuration
- **Service Configuration**: Use autowiring via `config/services.yaml`
- **Bundle Best Practices**: Follow Symfony Bundle conventions

### Documentation Requirements
- **PHPDoc**: Add PHPDoc blocks for public methods and classes
- **Parameter Documentation**: Document parameters with types and descriptions
- **Return Documentation**: Document return types and what they represent

### Exception Handling
- **Project Exceptions**: Use custom exceptions from `src/Exception/` or component-specific namespaces
- **Meaningful Messages**: Provide clear, actionable error messages
- **Note**: FHIRPath component exceptions extend `RuntimeException` (should be refactored for consistency)

## Generated Code Standards
All generated FHIR classes must:
- Be PSR-12 compliant
- Include PHPDoc annotations with ValueSet information
- Use strict types (`declare(strict_types=1);`)
- Include FHIR attributes (`FhirResource`, `FHIRBackboneElement`, `FHIRComplexType`, `FHIRPrimitive`)
- Use promoted constructor properties
- Follow version-specific namespaces (R4, R4B, R5)

## Security Guidelines
- **No Sensitive Data**: Never include sensitive information in logs or generated code
- **Input Validation**: Validate all external inputs and FHIR data
- **Error Handling**: Don't expose internal system details in error messages
