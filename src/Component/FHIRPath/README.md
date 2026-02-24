# FHIRPath Component

PHP implementation of the [FHIRPath 2.0 specification](http://hl7.org/fhirpath/N1/) for evaluating path expressions against FHIR resources.

## Features

- FHIRPath 2.0 expression evaluation (partial implementation - see [Implementation Status](#implementation-status))
- 90+ built-in functions (existence, filtering, string, math, date/time, type conversion, FHIR-specific)
- 15+ operators (arithmetic, comparison, logical, membership, type)
- FHIR-aligned type system with `is`/`as` operators
- Expression caching with LRU eviction
- Pre-compilation for repeated evaluation

## Quick Start

```php
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;

$service = new FHIRPathService();

// Evaluate against FHIR data
$result = $service->evaluate('Patient.name.given', $patient);

// Filtering
$result = $service->evaluate('name.where(use = "official").given.first()', $patient);

// Boolean check
$hasPhone = $service->evaluate('telecom.where(system = "phone").exists()', $patient);
```

### Compilation and Caching

```php
// Compile once, evaluate many times
$compiled = $service->compile('name.where(use = "official").given.first()');

foreach ($patients as $patient) {
    $result = $compiled->evaluate($patient);
}

// Cache statistics
$stats = $service->getCacheStats();
// ['hits' => 10, 'misses' => 2, 'size' => 5]
```

### Validation

```php
$isValid = $service->validate('name.given');        // true
$isValid = $service->validate('name.given.???');     // false
```

## Supported Operations

### Functions

```php
// Existence
$service->evaluate('name.exists()', $patient);
$service->evaluate('name.empty()', $patient);
$service->evaluate('telecom.all(system = "phone")', $patient);

// Filtering and projection
$service->evaluate('name.where(use = "official")', $patient);
$service->evaluate('name.select(given)', $patient);
$service->evaluate('name.first()', $patient);

// String
$service->evaluate('name.given.upper()', $patient);
$service->evaluate('name.family.substring(0, 3)', $patient);
$service->evaluate('name.given.matches("[A-Z].*")', $patient);

// Math
$service->evaluate('(42).abs()', null);
$service->evaluate('Observation.value.sum()', $bundle);

// Type
$service->evaluate('value.toInteger()', $observation);
$service->evaluate('value is Quantity', $observation);
```

### Operators

```php
// Comparison
$service->evaluate('age > 18', $patient);
$service->evaluate('status = "active"', $patient);

// Logical
$service->evaluate('age > 18 and status = "active"', $patient);
$service->evaluate('value > 100 or value < 0', $observation);

// Arithmetic
$service->evaluate('value * 2', $observation);
$service->evaluate('(value + 10) / 2', $observation);

// Union
$service->evaluate('name | telecom', $patient);
```

## Implementation Status

### ✅ Implemented Features

#### Functions (90+)

| Category | Functions | Status |
|----------|-----------|--------|
| **Existence** (13) | `empty()`, `exists()`, `all()`, `count()`, `allTrue()`, `anyTrue()`, `allFalse()`, `anyFalse()`, `subsetOf()`, `supersetOf()`, `isDistinct()`, `not()`, `repeat()` | ✅ Complete |
| **Filtering** (9) | `where()`, `select()`, `first()`, `last()`, `tail()`, `take()`, `skip()`, `single()`, `distinct()` | ✅ Complete |
| **Subsetting** (3) | `union()`, `intersect()`, `exclude()` | ✅ Complete |
| **String** (13) | `substring()`, `length()`, `startsWith()`, `endsWith()`, `contains()`, `indexOf()`, `upper()`, `lower()`, `replace()`, `replaceMatches()`, `matches()`, `matchesFull()`, `trim()`, `split()`, `toChars()` | ✅ Complete |
| **Math** (14) | `sum()`, `abs()`, `ceiling()`, `floor()`, `truncate()`, `round()`, `exp()`, `ln()`, `log()`, `power()`, `sqrt()`, `min()`, `max()`, `avg()` | ✅ Complete |
| **Date/Time** (5) | `now()`, `timeOfDay()`, `today()`, `toMilliseconds()`, `toSeconds()` | ✅ Complete |
| **Type** (2) | `ofType()`, `hasValue()` | ✅ Complete |
| **Tree Navigation** (2) | `children()`, `descendants()` | ✅ Complete |
| **Utility** (2) | `trace()`, `aggregate()` | ✅ Complete |
| **Combining** (2) | `combine()`, `iif()` | ✅ Complete |
| **Type Conversion** (16) | `toBoolean()`, `convertsToBoolean()`, `toInteger()`, `convertsToInteger()`, `toDecimal()`, `convertsToDecimal()`, `toDate()`, `convertsToDate()`, `toDateTime()`, `convertsToDateTime()`, `toTime()`, `convertsToTime()`, `toQuantity()`, `convertsToQuantity()`, `toString()`, `convertsToString()` | ✅ Complete |
| **FHIR-Specific** (6) | `extension()`, `getValue()`, `resolve()`, `memberOf()`, `conformsTo()`, `htmlChecks()` | ✅ Complete |
| **Precision** (3) | `precision()`, `lowBoundary()`, `highBoundary()` | ✅ Complete |
| **Comparison** (1) | `comparable()` | ✅ Complete |

#### Operators

| Category | Operators | Status |
|----------|-----------|--------|
| **Arithmetic** | `+`, `-`, `*`, `/`, `div`, `mod` | ✅ Complete |
| **Comparison** | `=`, `!=`, `<`, `>`, `<=`, `>=` | ✅ Complete |
| **Logical** | `and`, `or`, `xor`, `implies` | ✅ Complete |
| **String** | `&` (concatenation) | ✅ Complete |
| **Collection** | `\|` (union) | ✅ Complete |
| **Membership** | `in`, `contains` | ✅ Complete |
| **Type** | `is`, `as` | ✅ Complete |
| **Equivalence** | `~`, `!~` | ❌ Not implemented |

#### Language Features

| Feature | Status |
|---------|--------|
| Path navigation | ✅ Complete |
| Indexing `[n]` | ✅ Complete |
| Function calls | ✅ Complete |
| Literals (string, number, boolean, date/time, quantity) | ✅ Complete |
| Collection literals `{}` | ✅ Complete |
| External constants `%context`, etc. | ✅ Complete |
| Reserved identifiers `$this`, `$index`, `$total` | ✅ Complete |
| Expression compilation/caching | ✅ Complete |

### ⚠️ Known Issues

| Issue | Impact | Affected Tests |
|-------|--------|----------------|
| ~~**Operator Precedence**~~ | ~~Multiplication not prioritized over addition; `is` vs `\|` precedence incorrect~~ | ✅ **FIXED** |
| ~~**`matches()` Edge Cases**~~ | ~~Empty collection handling incorrect; DOTALL mode not enabled~~ | ✅ **FIXED** |
| ~~**`in`/`contains` Operators**~~ | ~~Not implemented~~ | ✅ **FIXED** |
| **Semantic Validation** | Doesn't reject ambiguous expressions without parentheses (e.g., `-1.convertsToInteger()`, `1 > 2 is Boolean`) | 3 spec tests fail |
| **FHIR Deserialization** | Most spec tests skip due to XML/JSON deserialization failures (Serialization component issue, not FHIRPath) | 854 spec tests skipped |
| **FunctionRegistry Singleton** | Static state issues when unit tests run before integration tests | Intermittent test failures |

### 📊 Test Coverage

- **Unit Tests**: ✅ Passing (function-level tests)
- **Integration Tests**: ✅ Passing (real-world expression evaluation)
- **Specification Conformance**: ⚠️ In Progress
  - 3 tests failing (semantic validation edge cases)
  - 63 function-not-implemented errors (`lowBoundary`, `highBoundary`, etc.)
  - 854 tests skipped (deserialization issues)
  - Run with: `composer test-fhirpath-spec`

See [tests/Integration/KNOWN_ISSUES.md](tests/Integration/KNOWN_ISSUES.md) for detailed issue tracking.

## Error Handling

```php
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;

try {
    $result = $service->evaluate('invalid..path', $patient);
} catch (FHIRPathException $e) {
    echo "FHIRPath error: {$e->getMessage()}";
}
```

## Requirements

- **PHP**: 8.3 or higher

## Testing

This component includes comprehensive test coverage:

### Unit Tests
```bash
composer test:fhir-path
```

### Specification Conformance Tests

The component includes official FHIR FHIRPath specification conformance tests that validate compliance against the FHIR specification test cases:

```bash
# Run FHIRPath specification tests
composer test-fhirpath-spec
```

**Note**: These tests are currently in development as the FHIRPath evaluator implementation is being completed. The tests automatically load test cases from the official `fhir/fhir-test-cases` package and track implementation progress.

The specification tests are maintained in a separate test suite to:
- Allow focused development on specific FHIRPath features
- Track progress toward full specification compliance  
- Prevent blocking other integration tests during development

## License

This component is released under the MIT License. See the [LICENSE](../../../LICENSE) file for details.
