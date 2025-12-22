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

### Multi-Project Structure Standards
- **Component Isolation**: Maintain clear boundaries between Bundle, CodeGeneration, and Serialization components
- **Namespace Organization**: Use proper component namespaces (`Ardenexal\FHIRTools\Component\*`)
- **Dependency Management**: Declare explicit dependencies between components in composer.json
- **Backward Compatibility**: Maintain compatibility aliases for legacy namespaces
- **Cross-Version Support**: Ensure compatibility with Symfony 6.4 and 7.4

### Symfony Framework Standards
- **Dependency Injection**: Use Symfony's DI container, avoid `new` keyword in commands and services
- **Console Components**: Leverage Symfony Console helpers and components
- **Service Configuration**: Use Symfony Autowiring for service configuration
- **Bundle Best Practices**: Follow Symfony Bundle conventions for FHIRBundle
- **Component Integration**: Properly integrate standalone components with Symfony

### Documentation Requirements
- **PHPDoc**: Add comprehensive PHPDoc blocks for all public methods and classes
- **Author Tags**: Include `@author` tags in class documentation
- **Parameter Documentation**: Document all parameters with types and descriptions
- **Return Documentation**: Document return types and what they represent
- **Component Documentation**: Maintain README.md files for each component
- **Architecture Documentation**: Keep architecture documentation up to date

### Exception Handling
- **Project-Specific Exceptions**: Use custom exceptions from component-specific `Exception/` namespaces
- **Component-Specific Exceptions**: Use appropriate exception types for each component
- **Meaningful Messages**: Provide clear, actionable error messages
- **Exception Hierarchy**: Maintain proper exception inheritance structure

### Code Quality Tools
- **PHP-CS-Fixer**: Run `composer run lint` to fix code style issues
- **PHPStan**: Run `composer run phpstan` for static analysis
- **PHPUnit**: Use PHPUnit 12+ for testing, no `void` return types, use `self::assert*`
- **Component Testing**: Test components independently and together

## Security Guidelines
- **No Sensitive Data**: Never include sensitive information in logs, comments, or generated code
- **Input Validation**: Validate all external inputs and FHIR data
- **Error Handling**: Don't expose internal system details in error messages
- **Component Security**: Ensure secure communication between components

## Generated Code Standards
All generated FHIR classes must:
- Be PSR-12 compliant
- Include comprehensive PHPDoc annotations
- Use strict types (`declare(strict_types=1);`)
- Follow consistent naming conventions
- Include proper namespace declarations
- Use component-appropriate namespaces

## Component-Specific Guidelines

### FHIRBundle Component
- Follow Symfony Bundle best practices
- Provide semantic configuration
- Register services properly in DI container
- Include Symfony Flex recipe

### CodeGeneration Component
- Maintain minimal dependencies
- Support standalone usage
- Provide clear interfaces
- Handle errors gracefully

### Serialization Component
- Integrate with Symfony Serializer
- Support standalone usage
- Provide validation capabilities
- Optimize for performance