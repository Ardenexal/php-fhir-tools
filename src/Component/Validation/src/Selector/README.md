# Selector Registry Component

## Overview

The SelectorRegistry provides precomputed path selectors that efficiently navigate FHIR resources to extract values for validation. It handles complex FHIR path expressions including nested properties, array navigation, and choice types.

## Key Features

- **Nested Element Paths**: Navigate through multiple levels (e.g., `Patient.name.family`)
- **Array Navigation**: Access specific array indices (e.g., `Patient.name[0].given`)
- **Choice Types**: Handle polymorphic elements (e.g., `value[x]` matches `valueString`, `valueInteger`, etc.)
- **Two-Level Caching**: In-memory + PSR-6 cache for optimal performance
- **Lazy Compilation**: Selectors created on-demand and cached

## Usage

### Basic Path Selection

```php
use Ardenexal\FHIRTools\Component\Validation\Selector\SelectorRegistry;

$registry = new SelectorRegistry($cache);

// Get a selector for a simple path
$selector = $registry->getSelector(
    'http://hl7.org/fhir/StructureDefinition/Patient',
    'Patient.id'
);

$patient = ['id' => '123', 'name' => [...]];
$values = $selector($patient);
// Returns: ['123']
```

### Nested Paths

```php
// Navigate nested structures
$selector = $registry->getSelector(
    'http://hl7.org/fhir/StructureDefinition/Patient',
    'Patient.name.family'
);

$patient = [
    'name' => [
        ['family' => 'Smith', 'given' => ['John']],
        ['family' => 'Doe', 'given' => ['Jane']]
    ]
];

$values = $selector($patient);
// Returns: ['Smith', 'Doe']
```

### Choice Types

Choice types in FHIR use `[x]` notation (e.g., `value[x]`) and can match multiple concrete types:

```php
$selector = $registry->getSelector(
    'http://hl7.org/fhir/StructureDefinition/Observation',
    'Observation.value[x]'
);

$observation = [
    'valueString' => 'High',
    'code' => ['text' => 'Blood Pressure']
];

$values = $selector($observation);
// Returns: ['High']
```

The selector automatically finds properties that start with `value` (excluding just `value` itself):
- `valueString` ✅ matches `value[x]`
- `valueInteger` ✅ matches `value[x]`
- `valueCodeableConcept` ✅ matches `value[x]`
- `value` ❌ does not match

### Array Index Access

Access specific elements in arrays:

```php
$selector = $registry->getSelector(
    'http://hl7.org/fhir/StructureDefinition/Patient',
    'Patient.name[0].family'
);

$patient = [
    'name' => [
        ['family' => 'Smith'],
        ['family' => 'Doe']
    ]
];

$values = $selector($patient);
// Returns: ['Smith']  (only first element)
```

### Cache Management

```php
// Clear all cached selectors
$registry->clearCache();

// Clear selectors for a specific profile
$registry->clearCache('http://hl7.org/fhir/StructureDefinition/Patient');
```

## Path Syntax

### Standard FHIR Paths

FHIR element paths follow this format:
- `ResourceType.property` - Simple property access
- `ResourceType.property.nested` - Nested property access
- `ResourceType.property[0].nested` - Array index access
- `ResourceType.property[x]` - Choice type (polymorphic)

**Important**: The resource type (first segment) is automatically removed when navigating the resource structure, as it's not part of the actual resource data.

### Examples

| Path | Description | Matches |
|------|-------------|---------|
| `Patient.id` | Patient ID | `['id' => '123']` |
| `Patient.name.family` | All family names | `['name' => [['family' => 'Smith']]]` |
| `Patient.name[0].given` | First name's given names | `['name' => [['given' => ['John']]]]` |
| `Observation.value[x]` | Any value type | `['valueString' => 'test']` |
| `Extension.value[x].coding.code` | Choice type with nesting | `['valueCodeableConcept' => ['coding' => [['code' => 'xyz']]]]` |

## Performance

### Caching Strategy

**Two-Level Cache**:
1. **In-Memory**: Fast access for repeated selectors in same request
2. **PSR-6 Cache**: Persistent across requests (24-hour TTL)

### Cache Keys

Format: `selector:<md5(profileUrl|elementPath)>`

Example: `selector:a3f8d9e2...` for `Patient.name.family`

### Performance Benefits

- **No Repeated Compilation**: Selectors compiled once, reused thousands of times
- **Batch-Friendly**: Multiple constraints on same path reuse same selector
- **Memory Efficient**: Closures are lightweight

## Implementation Details

### Selector Compilation

Selectors are compiled into PHP closures that:
1. Parse the path into segments
2. Navigate the resource structure recursively
3. Handle arrays, nulls, and missing properties gracefully
4. Return all matching values as an array

### Edge Case Handling

- **Null Values**: Filtered out, return empty array
- **Missing Properties**: Return empty array (no errors)
- **Empty Arrays**: Return empty array
- **Non-Array Values**: Handled as single values
- **Mixed Arrays**: Detect structure and recurse appropriately

### Array Iteration

When encountering an array property containing objects:
```php
// Resource structure
[
    'name' => [
        ['family' => 'Smith', 'given' => ['John']],
        ['family' => 'Doe', 'given' => ['Jane']]
    ]
];

// Path: Patient.name.family
// Result: ['Smith', 'Doe']  (iterates over array)
```

## Integration

SelectorRegistry integrates with:

- **ProfileConstraintRepository**: Precompute selectors for constraint paths
- **FHIRPathEvaluator**: Extract context values for expression evaluation
- **CardinalityValidator**: Count elements at specific paths
- **ValidationService**: Navigate resources during validation

## Testing

```bash
composer test -- tests/Unit/Component/Validation/Selector/
```

**Coverage**:
- 12 test cases
- 23 assertions
- 100% code coverage

**Test Scenarios**:
- Simple paths
- Nested paths
- Choice types
- Array indices
- Caching (in-memory and PSR-6)
- Edge cases (nulls, empty arrays, missing properties)

## Examples

### Cardinality Checking

```php
$selector = $registry->getSelector($profileUrl, 'Patient.identifier');
$identifiers = $selector($patient);
$count = count($identifiers);

if ($count < $minCardinality) {
    // Violation: too few identifiers
}
```

### FHIRPath Context

```php
// Extract value for FHIRPath evaluation
$selector = $registry->getSelector($profileUrl, 'Patient.contact.telecom');
$telecoms = $selector($patient);

foreach ($telecoms as $telecom) {
    // Evaluate FHIRPath constraint on each telecom
    $result = $fhirPath->evaluate('system = "phone"', $telecom);
}
```

## References

- [FHIR ElementDefinition](http://hl7.org/fhir/elementdefinition.html)
- [FHIR Path Grammar](http://hl7.org/fhir/fhirpath.html)
- [Choice Data Types](http://hl7.org/fhir/datatypes.html#choice)
