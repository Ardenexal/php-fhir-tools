# FHIR Profile Validation Component (Proof of Concept)

## Overview

This component provides FHIR profile validation capabilities for PHP FHIRTools. It implements a hybrid approach combining:
- **FHIRPath constraint evaluation** using the existing FHIRPath component
- **Symfony Validator integration** for cardinality and type checking
- **Dynamic profile loading** from StructureDefinitions

## Status

🚧 **Proof of Concept** - This is an initial implementation demonstrating the recommended architecture from the analysis document.

## Architecture

```
Validation Component
├── Model/
│   ├── ProfileConstraint.php      # Constraint model
│   ├── ValidationIssue.php        # Validation issue model
│   └── ValidationResult.php       # Aggregated results
├── Loader/
│   └── ProfileConstraintLoader.php # Load constraints from StructureDefinitions
├── Service/
│   └── ProfileValidationService.php # Main validation service
├── Constraint/
│   └── FHIRPathConstraint.php     # Symfony constraint for FHIRPath
└── Validator/
    └── FHIRPathConstraintValidator.php # Symfony validator

```

## Features

✅ **Multi-Profile Validation**: Validate against multiple FHIR profiles simultaneously
✅ **Dynamic Loading**: Load profiles at runtime without code generation
✅ **FHIRPath Support**: Evaluate FHIRPath constraint expressions
✅ **Symfony Integration**: Native integration with Symfony Validator
✅ **FHIR Compliant**: Returns results as FHIR OperationOutcome

## Key Classes

### ProfileConstraint
Represents a single validation constraint from a StructureDefinition element:
- Cardinality (min/max)
- FHIRPath expressions
- Type restrictions
- ValueSet bindings
- MustSupport flags

### ValidationIssue
Represents a validation failure or warning:
- Severity (error/warning/info)
- Element path
- Human-readable message
- Constraint key
- Profile URL

### ValidationResult
Aggregates validation issues:
- Check validation status
- Get errors/warnings separately
- Convert to FHIR OperationOutcome
- Export as array

## Usage Example

```php
use Ardenexal\FHIRTools\Component\Validation\Model\ProfileConstraint;
use Ardenexal\FHIRTools\Component\Validation\Model\ValidationResult;
use Ardenexal\FHIRTools\Component\Validation\Model\ValidationIssue;

// Create a constraint from a StructureDefinition element
$constraint = new ProfileConstraint(
    path: 'Patient.name',
    key: 'name-1',
    expression: 'family.exists()',
    human: 'Patient must have a family name',
    min: 1,
    max: '*',
    severity: 'error'
);

// Create validation result
$result = new ValidationResult();

// Add issues
$result->addIssue(ValidationIssue::error(
    'Patient.name',
    'Patient must have at least one name',
    'name-1',
    'http://example.org/StructureDefinition/MyPatient'
));

// Check result
if (!$result->isValid()) {
    echo "Validation failed with {$result->getErrorCount()} errors\n";
    
    foreach ($result->getErrors() as $error) {
        echo "  {$error->getPath()}: {$error->getMessage()}\n";
    }
}

// Convert to FHIR OperationOutcome
$operationOutcome = $result->toOperationOutcome();
```

## Integration Points

### With FHIRPath Component
```php
// Evaluate FHIRPath constraints
$fhirPathService = $container->get('fhir.path_service');
$result = $fhirPathService->evaluate('name.family.exists()', $patient);

if (!$result->toBoolean()) {
    $result->addIssue(ValidationIssue::error(
        'Patient.name.family',
        'Family name is required'
    ));
}
```

### With Symfony Validator
```php
// Use Symfony validator for cardinality
$validator = $container->get('validator');
$violations = $validator->validate($patient, [
    new Assert\NotBlank(),
    new Assert\Count(min: 1)
]);

foreach ($violations as $violation) {
    $result->addIssue(ValidationIssue::error(
        $violation->getPropertyPath(),
        $violation->getMessage()
    ));
}
```

### With Code Generation Component
```php
// Load StructureDefinitions from FHIR packages
$packageLoader = $container->get('fhir.package_loader');
$package = $packageLoader->load('hl7.fhir.us.core', '5.0.0');
$structureDefinition = $package->getStructureDefinition($profileUrl);

// Parse constraints from StructureDefinition
$constraintLoader = new ProfileConstraintLoader();
$constraints = $constraintLoader->load($structureDefinition);
```

## Next Steps

To complete this implementation, the following components need to be developed:

1. **ProfileConstraintLoader**: Parse StructureDefinitions and extract constraints
2. **ProfileValidationService**: Main validation orchestrator
3. **FHIRPathConstraintValidator**: Symfony validator using FHIRPath
4. **BindingValidator**: Validate against ValueSet bindings
5. **SlicingValidator**: Handle array slicing validation
6. **Symfony Bundle Integration**: Register services and configuration

See `/docs/FHIR_PROFILE_VALIDATION_ANALYSIS.md` for detailed implementation plan.

## References

- [FHIR Profile Validation Analysis](/docs/FHIR_PROFILE_VALIDATION_ANALYSIS.md)
- [FHIR Validation Specification](http://hl7.org/fhir/validation.html)
- [FHIRPath Specification](http://hl7.org/fhirpath/)
- [Symfony Validator Documentation](https://symfony.com/doc/current/validation.html)
