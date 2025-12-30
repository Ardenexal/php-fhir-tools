# FHIR Profile Validation Analysis & Implementation Guide

## Executive Summary

This document analyzes different approaches for implementing FHIR profile validation in PHP FHIRTools and provides recommendations based on:
1. Analysis of existing FHIR validators (HAPI FHIR, .NET FHIR validator, matchbox)
2. Current PHP FHIRTools architecture (multi-component design with FHIRPath support)
3. Requirement to validate against multiple profiles simultaneously
4. Integration with Symfony validation framework

**Recommended Approach**: Hybrid FHIRPath + Symfony Validator approach using constraint definitions from StructureDefinitions.

---

## Table of Contents

1. [Current State Analysis](#current-state-analysis)
2. [FHIR Profile Validation Background](#fhir-profile-validation-background)
3. [Approach Comparison](#approach-comparison)
4. [Implementation Options](#implementation-options)
5. [Recommended Architecture](#recommended-architecture)
6. [Implementation Roadmap](#implementation-roadmap)
7. [Code Examples](#code-examples)
8. [References](#references)

---

## Current State Analysis

### Existing Infrastructure

PHP FHIRTools already has several components that can support profile validation:

#### 1. **FHIRPath Component** (Phases 1-4 Complete)
- **Location**: `src/Component/FHIRPath/`
- **Capabilities**: 
  - Expression parsing and evaluation
  - Path navigation through FHIR resources
  - Function library (filtering, aggregation, string manipulation)
  - Type system with FHIR alignment
- **Status**: Production-ready, can evaluate constraints

#### 2. **Serialization Component**
- **Location**: `src/Component/Serialization/`
- **Current Validation**:
  - `FHIRValidator`: Basic structure validation
  - `FHIRSchemaValidator`: XML schema validation
  - Type validation during serialization
- **Limitations**: No profile-specific validation

#### 3. **Code Generation Component**
- **Location**: `src/Component/CodeGeneration/`
- **Capabilities**:
  - Parses StructureDefinitions
  - Generates PHP classes from FHIR definitions
  - PackageLoader can fetch FHIR packages
- **Potential**: Could be extended to generate validation rules

#### 4. **Symfony Validator Integration**
- **Already Installed**: `symfony/validator ^6.4|^7.4`
- **Not Currently Used**: For FHIR validation
- **Potential**: Ideal for declarative constraint validation

### Gaps Identified

1. **No Profile-Aware Validation**: Current validators don't check against profiles
2. **No Constraint Evaluation**: FHIRPath constraints from profiles aren't evaluated
3. **No Multi-Profile Support**: Cannot validate against multiple profiles simultaneously
4. **No Slicing Validation**: Slice definitions not validated
5. **No Binding Strength Checking**: ValueSet bindings not enforced

---

## FHIR Profile Validation Background

### What is a FHIR Profile?

A FHIR profile is a StructureDefinition that constrains a base FHIR resource:

```json
{
  "resourceType": "StructureDefinition",
  "url": "http://example.org/StructureDefinition/MyPatientProfile",
  "type": "Patient",
  "baseDefinition": "http://hl7.org/fhir/StructureDefinition/Patient",
  "derivation": "constraint",
  "differential": {
    "element": [
      {
        "path": "Patient.name",
        "min": 1,
        "constraint": [{
          "key": "name-1",
          "severity": "error",
          "human": "Patient must have family name",
          "expression": "family.exists()"
        }]
      }
    ]
  }
}
```

### Key Validation Aspects

1. **Cardinality**: min/max constraints (e.g., `Patient.name` must exist)
2. **Type Constraints**: Allowed types (e.g., `value[x]` must be `valueString`)
3. **FHIRPath Constraints**: Expression-based rules
4. **ValueSet Bindings**: Code must come from specified ValueSet
5. **Slicing**: Discriminator-based partitioning of arrays
6. **Extensions**: Profile-specific extensions
7. **MustSupport**: Elements that must be supported

---

## Approach Comparison

### How Other FHIR Implementations Handle Profiles

#### 1. **HAPI FHIR (Java)**

**Approach**: Runtime validation using parsed StructureDefinitions

```java
FhirValidator validator = fhirContext.newValidator();
validator.registerValidator(new FhirInstanceValidator(validationSupport));
ValidationResult result = validator.validateWithResult(patient);
```

**Architecture**:
- **StructureDefinition Parser**: Loads profiles into memory
- **Constraint Evaluator**: Evaluates FHIRPath expressions
- **Validation Support**: Resolves ValueSets and other profiles
- **Result Aggregator**: Collects all validation issues

**Pros**:
- Dynamic: Can validate against any profile at runtime
- Complete: Handles all FHIR validation aspects
- Multi-profile: Can validate against multiple profiles

**Cons**:
- Memory intensive: Loads entire profiles
- Performance: Runtime parsing and evaluation

#### 2. **.NET FHIR Validator (C#)**

**Approach**: Compiled validation rules with caching

```csharp
var validator = new Validator();
validator.Settings.ResourceResolver = new CachedResolver();
var result = validator.Validate(patient, "http://example.org/StructureDefinition/MyPatient");
```

**Architecture**:
- **Profile Snapshot Generator**: Pre-computes full element definitions
- **Constraint Compiler**: Converts FHIRPath to executable code
- **Validation Engine**: Executes compiled rules
- **Cache Layer**: Caches compiled validators

**Pros**:
- Fast: Pre-compiled rules
- Memory efficient: Caches compiled validators
- Type-safe: Uses strongly-typed classes

**Cons**:
- Build step: Requires compilation
- Less dynamic: Profile changes need recompilation

#### 3. **Matchbox (Swiss eHealth)**

**Approach**: FHIR Validator Wrapper with REST API

```bash
POST /fhir/Patient/$validate
{
  "resourceType": "Patient",
  "meta": {
    "profile": ["http://example.org/StructureDefinition/MyPatient"]
  }
}
```

**Architecture**:
- **Wrapper Service**: Wraps HAPI FHIR validator
- **REST API**: HTTP endpoints for validation
- **Profile Registry**: Manages available profiles
- **Result Transformer**: Converts to OperationOutcome

**Pros**:
- Service-based: No client-side dependencies
- Centralized: One validator for all systems
- Updated: Easy to update validation rules

**Cons**:
- Network dependency: Requires connectivity
- Performance: HTTP overhead
- Availability: Single point of failure

---

## Implementation Options

### Option 1: Pure Symfony Validator with Attributes

**Description**: Use Symfony validation attributes on generated FHIR classes

#### Implementation

```php
use Symfony\Component\Validator\Constraints as Assert;

#[FHIRProfile('http://example.org/StructureDefinition/MyPatient')]
class Patient extends FHIRResource
{
    #[Assert\NotBlank(groups: ['MyPatient'])]
    #[Assert\Count(min: 1, groups: ['MyPatient'])]
    public array $name = [];
    
    #[Assert\NotBlank(groups: ['MyPatient'])]
    public array $identifier = [];
}
```

#### Validation Usage

```php
$validator = new ValidatorBuilder();
$violations = $validator->validate($patient, null, ['MyPatient']);
```

**Pros**:
- ✅ Native Symfony integration
- ✅ IDE support with attributes
- ✅ Can validate with multiple groups (profiles)
- ✅ Performance: Cached validation metadata
- ✅ Familiar to Symfony developers

**Cons**:
- ❌ Requires code generation for each profile
- ❌ Cannot add profiles dynamically
- ❌ Limited to what Symfony validators can express
- ❌ FHIRPath constraints need custom validator
- ❌ Slicing and complex logic difficult

**Verdict**: ⚠️ Good for simple cardinality, but insufficient for complex profiles

---

### Option 2: JSON Schema-Based Validation

**Description**: Convert StructureDefinitions to JSON Schema, validate with JSON Schema validator

#### Implementation

```php
$schemaGenerator = new FHIRProfileToJsonSchema();
$schema = $schemaGenerator->generate($structureDefinition);

$validator = new JsonSchemaValidator();
$result = $validator->validate($patientJson, $schema);
```

**Example Schema**:
```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "type": "object",
  "required": ["name", "identifier"],
  "properties": {
    "name": {
      "type": "array",
      "minItems": 1,
      "items": { "$ref": "#/definitions/HumanName" }
    }
  }
}
```

**Pros**:
- ✅ Standard JSON Schema format
- ✅ Many validators available
- ✅ Can validate multiple schemas (profiles)
- ✅ Schema composition for complex validation

**Cons**:
- ❌ Cannot express FHIRPath constraints
- ❌ Limited support for FHIR-specific concepts (slicing, extensions)
- ❌ Conversion StructureDefinition → JSON Schema complex
- ❌ Binding strength not expressible
- ❌ Less readable error messages

**Verdict**: ⚠️ Partial solution, cannot handle FHIRPath constraints

---

### Option 3: Pure FHIRPath Constraint Evaluation

**Description**: Store constraints from StructureDefinitions, evaluate with FHIRPath engine

#### Implementation

```php
class ProfileConstraintRegistry
{
    private array $constraints = [];
    
    public function loadProfile(StructureDefinition $profile): void
    {
        foreach ($profile->differential->element as $element) {
            foreach ($element->constraint ?? [] as $constraint) {
                $this->constraints[] = [
                    'path' => $element->path,
                    'key' => $constraint->key,
                    'expression' => $constraint->expression,
                    'severity' => $constraint->severity,
                    'human' => $constraint->human
                ];
            }
        }
    }
    
    public function validate(FHIRResource $resource, FHIRPathService $fhirPath): array
    {
        $errors = [];
        foreach ($this->constraints as $constraint) {
            $result = $fhirPath->evaluate($constraint['expression'], $resource);
            if (!$result->toBoolean()) {
                $errors[] = [
                    'path' => $constraint['path'],
                    'message' => $constraint['human']
                ];
            }
        }
        return $errors;
    }
}
```

**Pros**:
- ✅ Handles all FHIRPath constraints natively
- ✅ Dynamic: Load any profile at runtime
- ✅ Multi-profile: Validate multiple profiles easily
- ✅ Uses existing FHIRPath component
- ✅ Accurate to FHIR specification

**Cons**:
- ❌ No Symfony integration
- ❌ Performance: Interprets expressions every time
- ❌ Need to handle cardinality separately
- ❌ ValueSet binding needs additional logic
- ❌ Slicing requires special handling

**Verdict**: ✅ Best for FHIR-specific constraints, needs supplementation

---

### Option 4: Hybrid FHIRPath + Symfony Validator (RECOMMENDED)

**Description**: Combine FHIRPath constraint evaluation with Symfony validation framework

#### Architecture

```
┌─────────────────────────────────────────────────────────┐
│            FHIRProfileValidator                         │
│  (Main validation orchestrator)                         │
└────────────────────┬────────────────────────────────────┘
                     │
        ┌────────────┴────────────┐
        │                         │
        ▼                         ▼
┌──────────────────┐    ┌─────────────────────┐
│ Symfony          │    │ FHIRPath            │
│ Validator        │    │ Constraint          │
│                  │    │ Evaluator           │
│ - Cardinality    │    │ - FHIRPath expr.    │
│ - Type checks    │    │ - Custom logic      │
│ - Required       │    │ - Cross-element     │
└──────────────────┘    └─────────────────────┘
        │                         │
        │                         │
        ▼                         ▼
┌─────────────────────────────────────────┐
│  ProfileConstraintRepository            │
│  (Stores parsed profile constraints)    │
└─────────────────────────────────────────┘
```

#### Implementation

```php
// 1. Profile constraint model
class ProfileConstraint
{
    public function __construct(
        public string $path,
        public string $key,
        public ?string $expression,
        public int $min,
        public string $max,
        public array $types,
        public ?array $binding,
        public string $severity = 'error'
    ) {}
}

// 2. Profile constraint repository
class ProfileConstraintRepository
{
    private array $profiles = [];
    
    public function load(string $profileUrl, StructureDefinition $sd): void
    {
        $constraints = [];
        foreach ($sd->differential->element ?? [] as $element) {
            $constraints[] = new ProfileConstraint(
                path: $element->path,
                key: $element->id ?? '',
                expression: $element->constraint[0]->expression ?? null,
                min: $element->min ?? 0,
                max: $element->max ?? '*',
                types: $element->type ?? [],
                binding: $element->binding ?? null
            );
        }
        $this->profiles[$profileUrl] = $constraints;
    }
    
    public function get(string $profileUrl): array
    {
        return $this->profiles[$profileUrl] ?? [];
    }
}

// 3. Custom Symfony constraint for FHIRPath
#[\Attribute]
class FHIRPathConstraint extends Constraint
{
    public string $expression;
    public string $message = 'FHIRPath constraint violation: {{ expression }}';
}

// 4. FHIRPath constraint validator
class FHIRPathConstraintValidator extends ConstraintValidator
{
    public function __construct(
        private FHIRPathService $fhirPathService
    ) {}
    
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRPathConstraint) {
            throw new UnexpectedTypeException($constraint, FHIRPathConstraint::class);
        }
        
        $result = $this->fhirPathService->evaluate($constraint->expression, $value);
        
        if (!$result->toBoolean()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ expression }}', $constraint->expression)
                ->addViolation();
        }
    }
}

// 5. Main profile validator
class FHIRProfileValidator
{
    public function __construct(
        private ValidatorInterface $symfonyValidator,
        private FHIRPathService $fhirPathService,
        private ProfileConstraintRepository $repository
    ) {}
    
    public function validate(
        FHIRResource $resource, 
        array $profileUrls
    ): ConstraintViolationList {
        $allViolations = new ConstraintViolationList();
        
        foreach ($profileUrls as $profileUrl) {
            // Get constraints for this profile
            $constraints = $this->repository->get($profileUrl);
            
            foreach ($constraints as $constraint) {
                // Symfony validation for cardinality/types
                if ($constraint->min > 0 || $constraint->max !== '*') {
                    $violations = $this->validateCardinality(
                        $resource, 
                        $constraint
                    );
                    $allViolations->addAll($violations);
                }
                
                // FHIRPath validation for constraints
                if ($constraint->expression) {
                    $violations = $this->validateFHIRPath(
                        $resource, 
                        $constraint
                    );
                    $allViolations->addAll($violations);
                }
                
                // ValueSet binding validation
                if ($constraint->binding) {
                    $violations = $this->validateBinding(
                        $resource, 
                        $constraint
                    );
                    $allViolations->addAll($violations);
                }
            }
        }
        
        return $allViolations;
    }
    
    private function validateCardinality(
        FHIRResource $resource,
        ProfileConstraint $constraint
    ): ConstraintViolationList {
        // Use Symfony validator for cardinality
        $violations = new ConstraintViolationList();
        $path = $this->resolvePath($constraint->path, $resource);
        
        if ($constraint->min > 0 && empty($path)) {
            $violation = new ConstraintViolation(
                "Element {$constraint->path} is required (min={$constraint->min})",
                null,
                [],
                $resource,
                $constraint->path,
                $path
            );
            $violations->add($violation);
        }
        
        return $violations;
    }
    
    private function validateFHIRPath(
        FHIRResource $resource,
        ProfileConstraint $constraint
    ): ConstraintViolationList {
        $violations = new ConstraintViolationList();
        
        $result = $this->fhirPathService->evaluate(
            $constraint->expression,
            $resource
        );
        
        if (!$result->toBoolean()) {
            $violation = new ConstraintViolation(
                "FHIRPath constraint failed: {$constraint->key}",
                null,
                [],
                $resource,
                $constraint->path,
                null
            );
            $violations->add($violation);
        }
        
        return $violations;
    }
    
    private function validateBinding(
        FHIRResource $resource,
        ProfileConstraint $constraint
    ): ConstraintViolationList {
        // Validate against ValueSet binding
        // Implementation depends on ValueSet resolution
        return new ConstraintViolationList();
    }
}

// 6. Service registration in Symfony
// config/services.yaml
services:
    fhir.profile_constraint_repository:
        class: Ardenexal\FHIRTools\Component\Validation\ProfileConstraintRepository
        
    fhir.profile_validator:
        class: Ardenexal\FHIRTools\Component\Validation\FHIRProfileValidator
        arguments:
            - '@validator'
            - '@fhir.path_service'
            - '@fhir.profile_constraint_repository'
```

#### Usage Example

```php
// Load profiles
$loader = new ProfileLoader($packageLoader);
$patientProfile = $loader->load('http://example.org/StructureDefinition/MyPatient');
$repository->load($patientProfile->url, $patientProfile);

// Validate patient against multiple profiles
$patient = new FHIRPatient();
$patient->name = [/* ... */];

$violations = $profileValidator->validate($patient, [
    'http://hl7.org/fhir/StructureDefinition/Patient', // Base
    'http://example.org/StructureDefinition/MyPatient'  // Custom
]);

if (count($violations) > 0) {
    foreach ($violations as $violation) {
        echo "{$violation->getPropertyPath()}: {$violation->getMessage()}\n";
    }
}
```

**Pros**:
- ✅ Leverages existing Symfony validator infrastructure
- ✅ Uses existing FHIRPath component (already implemented)
- ✅ Multi-profile support (validate against multiple profiles)
- ✅ Dynamic: Load profiles at runtime
- ✅ Handles all FHIR constraint types
- ✅ Integration with Symfony ecosystem
- ✅ Clear separation of concerns
- ✅ Extensible: Easy to add new constraint types

**Cons**:
- ⚠️ More complex than single-approach solutions
- ⚠️ Performance overhead (can be mitigated with caching)
- ⚠️ Requires StructureDefinition parsing

**Verdict**: ✅✅✅ **RECOMMENDED** - Best balance of flexibility, completeness, and integration

---

## Recommended Architecture

### Component Structure

```
src/Component/Validation/
├── src/
│   ├── FHIRProfileValidator.php          # Main validator
│   ├── ProfileConstraintRepository.php    # Stores constraints
│   ├── Model/
│   │   ├── ProfileConstraint.php         # Constraint model
│   │   ├── ValidationResult.php          # Result model
│   │   └── ValidationIssue.php           # Issue model
│   ├── Constraint/
│   │   ├── FHIRPathConstraint.php        # Symfony constraint
│   │   ├── CardinalityConstraint.php     # Cardinality
│   │   ├── BindingConstraint.php         # ValueSet binding
│   │   └── SlicingConstraint.php         # Slicing
│   ├── Validator/
│   │   ├── FHIRPathConstraintValidator.php
│   │   ├── CardinalityValidator.php
│   │   ├── BindingValidator.php
│   │   └── SlicingValidator.php
│   ├── Loader/
│   │   ├── ProfileLoader.php             # Load profiles
│   │   └── ProfileParser.php             # Parse StructureDefinitions
│   ├── Service/
│   │   └── ValidationService.php         # High-level API
│   └── Exception/
│       ├── ValidationException.php
│       └── ProfileNotFoundException.php
├── tests/
│   ├── Unit/
│   └── Integration/
├── composer.json
└── README.md
```

### Integration Points

#### 1. **FHIRPath Component**
```php
// Use for constraint expression evaluation
$result = $this->fhirPathService->evaluate(
    "name.family.exists()",
    $patient
);
```

#### 2. **Symfony Validator**
```php
// Use for cardinality, type checks
$violations = $this->validator->validate($patient, [
    new Assert\NotBlank(),
    new Assert\Count(min: 1)
]);
```

#### 3. **CodeGeneration Component**
```php
// Use to load StructureDefinitions from packages
$package = $packageLoader->load('hl7.fhir.us.core', '5.0.0');
$structureDefinition = $package->getStructureDefinition($url);
```

#### 4. **Serialization Component**
```php
// Integrate validation into serialization flow
$context = FHIRSerializationContext::forJson()
    ->withProfileValidation(['http://example.org/StructureDefinition/MyPatient']);
```

### Configuration

```yaml
# config/packages/fhir.yaml
fhir:
    validation:
        enabled: true
        cache_enabled: true
        cache_directory: '%kernel.cache_dir%/fhir_profiles'
        default_profiles: []
        strict_mode: false  # false = warnings, true = errors
        
        # Profile sources
        profile_sources:
            - type: 'package'
              package: 'hl7.fhir.us.core'
              version: '5.0.0'
            - type: 'directory'
              path: '%kernel.project_dir%/config/fhir/profiles'
            - type: 'url'
              url: 'http://example.org/fhir/profiles'
```

---

## Implementation Roadmap

### Phase 1: Foundation (Week 1-2)

**Tasks**:
- [ ] Create `Validation` component structure
- [ ] Implement `ProfileConstraint` model
- [ ] Implement `ProfileConstraintRepository`
- [ ] Add basic `ProfileLoader`
- [ ] Write unit tests for models

**Deliverables**:
- Component skeleton
- Constraint storage
- Profile loading from StructureDefinitions

### Phase 2: Basic Validation (Week 3-4)

**Tasks**:
- [ ] Implement `CardinalityConstraintValidator`
- [ ] Implement `FHIRPathConstraintValidator`
- [ ] Create `FHIRProfileValidator` orchestrator
- [ ] Integrate with Symfony validator
- [ ] Write validation tests

**Deliverables**:
- Cardinality validation working
- FHIRPath constraint validation working
- Multi-profile validation support

### Phase 3: Advanced Features (Week 5-6)

**Tasks**:
- [ ] Implement `BindingConstraintValidator`
- [ ] Implement `SlicingConstraintValidator`
- [ ] Add type constraint validation
- [ ] Add extension validation
- [ ] Performance optimization (caching)

**Deliverables**:
- Complete validation coverage
- ValueSet binding validation
- Slicing support

### Phase 4: Integration & Polish (Week 7-8)

**Tasks**:
- [ ] Symfony Bundle integration
- [ ] Console commands for validation
- [ ] Error message improvement
- [ ] Documentation
- [ ] Example profiles and tests

**Deliverables**:
- CLI tool: `php bin/console fhir:validate`
- Complete documentation
- Integration tests
- Performance benchmarks

---

## Code Examples

### Example 1: Validate Patient Against US Core Profile

```php
use Ardenexal\FHIRTools\Component\Validation\Service\ValidationService;

$validationService = $container->get('fhir.validation_service');

$patient = new FHIRPatient();
$patient->identifier = [/* ... */];
$patient->name = [/* ... */];

// Validate against US Core Patient profile
$result = $validationService->validate($patient, [
    'http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient'
]);

if (!$result->isValid()) {
    foreach ($result->getIssues() as $issue) {
        echo sprintf(
            "[%s] %s: %s\n",
            $issue->getSeverity(),
            $issue->getPath(),
            $issue->getMessage()
        );
    }
}
```

### Example 2: Custom Profile Definition

```php
// Load custom profile from StructureDefinition
$profileLoader = $container->get('fhir.profile_loader');
$profile = $profileLoader->loadFromFile('config/fhir/profiles/MyPatient.json');

// Register profile
$repository = $container->get('fhir.profile_constraint_repository');
$repository->load($profile->url, $profile);

// Now validate against custom profile
$result = $validationService->validate($patient, [
    'http://example.org/StructureDefinition/MyPatient'
]);
```

### Example 3: Symfony Console Command

```php
namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ValidateResourceCommand extends Command
{
    protected static $defaultName = 'fhir:validate';
    
    public function __construct(
        private ValidationService $validationService,
        private FHIRSerializationService $serializer
    ) {
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this
            ->setDescription('Validate a FHIR resource against profiles')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to FHIR resource JSON/XML')
            ->addOption('profile', 'p', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Profile URL(s)')
            ->addOption('format', 'f', InputOption::VALUE_REQUIRED, 'Format (json|xml)', 'json');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getArgument('file');
        $profiles = $input->getOption('profile');
        $format = $input->getOption('format');
        
        // Load resource
        $content = file_get_contents($file);
        $resource = $this->serializer->deserialize($content, $format);
        
        // Validate
        $result = $this->validationService->validate($resource, $profiles);
        
        // Output results
        if ($result->isValid()) {
            $output->writeln('<info>✓ Validation passed</info>');
            return Command::SUCCESS;
        }
        
        $output->writeln('<error>✗ Validation failed</error>');
        foreach ($result->getIssues() as $issue) {
            $output->writeln(sprintf(
                '  [%s] %s: %s',
                $issue->getSeverity(),
                $issue->getPath(),
                $issue->getMessage()
            ));
        }
        
        return Command::FAILURE;
    }
}
```

### Example 4: Integration with Serialization

```php
// Add profile validation to serialization context
$context = FHIRSerializationContext::forJson()
    ->withProfileValidation([
        'http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient'
    ])
    ->withValidationMode(FHIRSerializationContext::VALIDATION_STRICT);

// Deserialization with validation
try {
    $patient = $serializer->deserialize(
        $json, 
        FHIRPatient::class, 
        'json', 
        $context->toSymfonyContext()
    );
} catch (ValidationException $e) {
    // Handle validation errors
    foreach ($e->getViolations() as $violation) {
        echo $violation->getMessage();
    }
}
```

---

## References

### FHIR Specification
- [FHIR Validation](http://hl7.org/fhir/validation.html)
- [StructureDefinition Resource](http://hl7.org/fhir/structuredefinition.html)
- [FHIRPath Specification](http://hl7.org/fhirpath/)
- [Profiling FHIR](http://hl7.org/fhir/profiling.html)

### Other Implementations
- [HAPI FHIR Validator](https://github.com/hapifhir/hapi-fhir) (Java)
- [Firely .NET SDK](https://github.com/FirelyTeam/firely-net-sdk) (C#)
- [matchbox](https://github.com/ahdis/matchbox) (Swiss eHealth)
- [FHIR Validator CLI](https://confluence.hl7.org/display/FHIR/Using+the+FHIR+Validator)

### Symfony Resources
- [Symfony Validator Component](https://symfony.com/doc/current/validation.html)
- [Creating Custom Constraints](https://symfony.com/doc/current/validation/custom_constraint.html)
- [Validation Groups](https://symfony.com/doc/current/validation/groups.html)

---

## Conclusion

The **Hybrid FHIRPath + Symfony Validator approach** provides the best solution for FHIR profile validation in PHP FHIRTools because it:

1. ✅ **Leverages existing components**: Uses FHIRPath (already implemented) and Symfony Validator (already installed)
2. ✅ **Supports multiple profiles**: Can validate against multiple profiles simultaneously
3. ✅ **Dynamic and flexible**: Load profiles at runtime without code generation
4. ✅ **Complete coverage**: Handles all FHIR validation aspects (cardinality, FHIRPath, bindings, slicing)
5. ✅ **Symfony integration**: Native integration with Symfony ecosystem
6. ✅ **Extensible**: Easy to add new constraint types and validators

This approach avoids the limitations of:
- Pure attribute-based validation (requires code generation for each profile)
- JSON Schema (cannot express FHIRPath constraints)
- Pure FHIRPath (lacks Symfony integration)

The implementation roadmap provides a clear path forward with 8 weeks of development divided into 4 phases, resulting in a production-ready FHIR profile validation system.
