# FHIR Validation Violation Mapping

This component maps FHIR validation issues to Symfony `ConstraintViolationList` format, implementing the production requirement for standardized error output.

## Overview

The `ViolationMapper` converts `ValidationIssue` objects into Symfony `ConstraintViolation` objects with:

- **Machine-readable codes** (e.g., `fhir.cardinality.min`, `fhir.binding.required`)
- **Precise property paths** (e.g., `name[0].family`)
- **Enriched messages** with profile context and constraint keys
- **Metadata parameters** for structured error handling

## Usage

### Basic Mapping

```php
use Ardenexal\FHIRTools\Component\Validation\Model\ValidationResult;
use Ardenexal\FHIRTools\Component\Validation\Violation\ViolationMapper;

// Create validation result with issues
$result = new ValidationResult();
$result->addIssue(ValidationIssue::error(
    'Patient.name',
    'Minimum cardinality not met',
    'cardinality-min',
    'http://hl7.org/fhir/StructureDefinition/Patient'
));

// Map to Symfony violations
$mapper = new ViolationMapper();
$violations = $mapper->map($result, $patient);

// Or use convenience method on ValidationResult
$violations = $result->toViolationList($patient);
```

### Violation Codes

Machine-readable codes for structured error handling:

| Code | Description | Example |
|------|-------------|---------|
| `fhir.cardinality.min` | Minimum cardinality violation | Element required but missing |
| `fhir.cardinality.max` | Maximum cardinality violation | Too many elements present |
| `fhir.type` | Type mismatch | Expected Quantity, found string |
| `fhir.fhirpath` | FHIRPath constraint failure | `family.exists() or given.exists()` failed |
| `fhir.binding.required` | Required binding violation | Code not in required ValueSet |
| `fhir.binding.extensible` | Extensible binding warning | Code not in preferred ValueSet |
| `fhir.slice.closed` | Closed slicing violation | Unmatched element in closed slice |
| `fhir.slice.cardinality` | Slice cardinality violation | Slice min/max not met |
| `fhir.profile.conflict` | Profile conflict detected | Multiple profiles with incompatible constraints |
| `fhir.mustsupport` | MustSupport violation | Required element not present |
| `fhir.invariant` | General invariant failure | Generic constraint violation |

### Property Path Normalization

The mapper normalizes FHIR paths to Symfony format:

```php
// Input: "Patient.name.family"
// Output: "name.family"

// Input: "Observation.component[0].code"
// Output: "component[0].code"
```

### Message Enrichment

Messages are automatically enriched with context:

```php
// Original message
"Name is required"

// Enriched message
"Name is required (Profile: us core patient) [name-required]"
```

### Direct Violation Creation

Create violations without going through `ValidationResult`:

```php
$violation = $mapper->createViolation(
    ValidationIssue::SEVERITY_ERROR,
    'Patient.name',
    'Name is required',
    'name-required',
    'http://hl7.org/fhir/StructureDefinition/Patient',
    $patient
);
```

## API Platform Integration

Use with API Platform for standardized validation error responses:

```php
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class FHIRValidationListener
{
    public function onKernelView(ViewEvent $event): void
    {
        $resource = $event->getControllerResult();
        
        // Validate FHIR resource
        $result = $this->validator->validate($resource, $profiles);
        
        if (!$result->isValid()) {
            // Convert to Symfony violations
            $violations = $result->toViolationList($resource);
            
            // API Platform will automatically format this
            throw new ValidationFailedException($resource, $violations);
        }
    }
}
```

## Production Benefits

✅ **Standardized Format**: All validation errors use Symfony's standard format  
✅ **Machine-Readable**: Structured codes enable automated error handling  
✅ **Precise Paths**: Property paths help clients identify exact error locations  
✅ **Rich Context**: Enriched messages include profile and constraint information  
✅ **API Platform Compatible**: Works seamlessly with API Platform error formatting  

## Architecture

```
ValidationResult
    └── toViolationList()
            └── ViolationMapper
                    ├── map() - Convert all issues
                    ├── mapIssue() - Convert single issue
                    ├── deriveCode() - Determine machine code
                    ├── normalizePropertyPath() - Format path
                    └── enrichMessage() - Add context
```

## Testing

Run unit tests:

```bash
composer test tests/Unit/Component/Validation/Violation/
```

Coverage:
- All 11 violation codes
- Property path normalization
- Message enrichment
- Profile name extraction
- Parameter preservation
- Root value handling
