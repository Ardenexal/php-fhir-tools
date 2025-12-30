# FHIR Profile Validation - Quick Reference

## TL;DR - Recommended Solution

**Use a Hybrid FHIRPath + Symfony Validator approach** that combines:
1. FHIRPath expressions for complex constraints (uses your existing FHIRPath component)
2. Symfony Validator for cardinality/type checks (already installed)
3. Runtime profile loading from StructureDefinitions (no code generation needed)

This approach allows you to **validate against multiple profiles simultaneously** without generating classes for each profile.

---

## Why Not Generate Classes Per Profile?

You're right that generating classes per profile isn't ideal for multi-profile validation. Here's why:

### Problems with Code Generation Approach
```php
// Would need separate classes for each profile
class Patient_BaseProfile { }
class Patient_USCoreProfile extends Patient_BaseProfile { }
class Patient_CustomProfile extends Patient_USCoreProfile { }

// Can't validate against multiple profiles at once
// Symfony validation groups limited to single class
```

**Issues**:
- ❌ Can't validate one instance against multiple profiles
- ❌ Requires code generation for every profile
- ❌ Profile changes need regeneration
- ❌ Complex inheritance chains
- ❌ Limited by what Symfony attributes can express

---

## Recommended Architecture

### How It Works

```
┌─────────────────────────────────────────────┐
│  Your FHIR Resource (Patient, Observation)  │
└────────────────┬────────────────────────────┘
                 │
                 ▼
┌────────────────────────────────────────────────┐
│        ProfileValidationService                │
│  (Validates against multiple profile URLs)     │
└────────────┬───────────────────────────────────┘
             │
    ┌────────┴─────────┐
    │                  │
    ▼                  ▼
┌──────────┐    ┌─────────────────┐
│ Symfony  │    │ FHIRPath        │
│ Validator│    │ Evaluator       │
│          │    │                 │
│ - Card.  │    │ - Expressions   │
│ - Types  │    │ - Complex logic │
└──────────┘    └─────────────────┘
```

### Key Components

1. **ProfileConstraint Model** - Represents a single constraint
   ```php
   new ProfileConstraint(
       path: 'Patient.name',
       expression: 'family.exists()',
       min: 1,
       max: '*',
       severity: 'error'
   );
   ```

2. **ProfileConstraintRepository** - Stores constraints loaded from StructureDefinitions
   ```php
   $repository->load($profileUrl, $structureDefinition);
   ```

3. **ProfileValidationService** - Orchestrates validation
   ```php
   $result = $validator->validate($patient, [
       'http://hl7.org/fhir/StructureDefinition/Patient',
       'http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient'
   ]);
   ```

---

## Usage Example

```php
// 1. Load profiles from StructureDefinitions (once, then cache)
$loader = new ProfileLoader($packageLoader);
$usCore = $loader->load('http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient');
$repository->load($usCore->url, $usCore);

// 2. Validate your patient against multiple profiles
$patient = new FHIRPatient();
$patient->name = [/* ... */];

$result = $validationService->validate($patient, [
    'http://hl7.org/fhir/StructureDefinition/Patient',      // Base FHIR
    'http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient',  // US Core
    'http://example.org/StructureDefinition/my-custom-patient'          // Your custom
]);

// 3. Check results
if (!$result->isValid()) {
    foreach ($result->getErrors() as $error) {
        echo "{$error->getPath()}: {$error->getMessage()}\n";
        echo "  Profile: {$error->getProfile()}\n";
    }
}

// 4. Return as FHIR OperationOutcome
return $result->toOperationOutcome();
```

---

## What's Different from Other Approaches?

### ❌ Symfony Attributes Only
```php
// Limited: Can't express FHIRPath
#[Assert\NotBlank]
public array $name = [];
```

### ❌ JSON Schema
```php
// Limited: Can't express FHIRPath constraints
"required": ["name", "identifier"]
```

### ❌ Pure FHIRPath
```php
// Limited: No Symfony integration
$errors = $fhirPath->evaluate('name.exists()', $patient);
```

### ✅ Recommended Hybrid
```php
// Best of both worlds:
// - Symfony for simple constraints
// - FHIRPath for complex logic
// - Multi-profile support
// - Dynamic profile loading
```

---

## How Other FHIR Implementations Do It

### HAPI FHIR (Java)
```java
// Runtime validation, similar to recommended approach
FhirValidator validator = context.newValidator();
validator.registerValidator(new FhirInstanceValidator(support));
ValidationResult result = validator.validateWithResult(patient);
```

### .NET FHIR Validator (C#)
```csharp
// Pre-compiled rules for performance
var validator = new Validator();
var result = validator.Validate(patient, profileUrl);
```

### Matchbox (Swiss eHealth)
```bash
// REST API wrapper around HAPI
POST /fhir/Patient/$validate
```

**Your approach** would be closest to HAPI FHIR but with better Symfony integration.

---

## Implementation Timeline

### Phase 1: Foundation (2 weeks)
- ProfileConstraint model ✅ (Done in POC)
- ProfileConstraintRepository
- StructureDefinition parser

### Phase 2: Basic Validation (2 weeks)
- Cardinality validation
- FHIRPath constraint evaluation
- Multi-profile orchestration

### Phase 3: Advanced (2 weeks)
- ValueSet binding validation
- Slicing support
- Extension validation

### Phase 4: Polish (2 weeks)
- Symfony Bundle integration
- Console commands
- Documentation
- Performance optimization

**Total: 8 weeks**

---

## Files to Review

1. **Full Analysis**: `/docs/FHIR_PROFILE_VALIDATION_ANALYSIS.md`
   - Detailed comparison of all approaches
   - Complete architecture diagrams
   - Implementation roadmap

2. **POC Code**: `/src/Component/Validation/`
   - Working model classes
   - Demonstrates the architecture

3. **Example**: `/examples/validation-proof-of-concept.php`
   - Run with: `php examples/validation-proof-of-concept.php`
   - Shows how constraints and validation work

---

## Key Advantages

1. ✅ **Multi-profile validation**: Validate against multiple profiles at once
2. ✅ **No code generation**: Load profiles dynamically from StructureDefinitions
3. ✅ **Uses existing components**: FHIRPath (already implemented) + Symfony Validator (already installed)
4. ✅ **Extensible**: Easy to add new constraint types
5. ✅ **FHIR compliant**: Returns standard OperationOutcome
6. ✅ **Flexible**: Works with any FHIR version (R4, R4B, R5)

---

## Questions?

The comprehensive analysis document answers:
- Why each approach was evaluated
- How other languages handle this
- Detailed code examples
- Complete implementation plan
- Integration with existing components

See: `/docs/FHIR_PROFILE_VALIDATION_ANALYSIS.md`
