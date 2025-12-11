---
inclusion: always
---

# PHP FHIRTools Development Standards

## Core Requirements

### PHP Standards
- **PHP Version**: 8.2+ required
- **Strict Types**: Always include `declare(strict_types=1);` at the top of every PHP file
- **PSR-12 Compliance**: All code must follow PSR-12 coding standards
- **Type Hints**: Use strict type hints for all parameters and return types

### Symfony Framework Standards
- **Dependency Injection**: Use Symfony's DI container, avoid `new` keyword in commands and services
- **Console Components**: Leverage Symfony Console helpers and components
- **Service Configuration**: Use Symfony Autowiring for service configuation
- **Best Practices**: Follow Symfony best practices for command structure and service design

### Documentation Requirements
- **PHPDoc**: Add comprehensive PHPDoc blocks for all public methods and classes
- **Author Tags**: Include `@author` tags in class documentation
- **Parameter Documentation**: Document all parameters with types and descriptions
- **Return Documentation**: Document return types and what they represent

### Exception Handling
- **Project-Specific Exceptions**: Use custom exceptions from `src/Exception/` namespace
- **Meaningful Messages**: Provide clear, actionable error messages
- **Exception Hierarchy**: Maintain proper exception inheritance structure

### Code Quality Tools
- **PHP-CS-Fixer**: Run `composer run lint` to fix code style issues
- **PHPStan**: Run `composer run phpstan` for static analysis
- **PHPUnit**: Use PHPUnit 12+ for testing, no `void` return types, use `self::assert*`

## Security Guidelines
- **No Sensitive Data**: Never include sensitive information in logs, comments, or generated code
- **Input Validation**: Validate all external inputs and FHIR data
- **Error Handling**: Don't expose internal system details in error messages

## Generated Code Standards
All generated FHIR classes must:
- Be PSR-12 compliant
- Include comprehensive PHPDoc annotations
- Use strict types (`declare(strict_types=1);`)
- Follow consistent naming conventions
- Include proper namespace declarations