# FHIRPath Component

Standalone PHP library for evaluating FHIRPath 2.0 expressions against FHIR resources. This component can be used independently or as part of the FHIRBundle in Symfony applications.

## Features

- **Standalone Library**: Use independently without Symfony
- **FHIRPath 2.0 Compliant**: Full implementation of FHIRPath 2.0 specification
- **Expression Evaluation**: Evaluate path expressions against FHIR resources
- **50+ Functions**: Complete function library (existence, filtering, string, math, date/time, type conversion)
- **20+ Operators**: Full operator support with correct precedence
- **Type System**: FHIR-aligned type system with `is`/`as` operators
- **Service Layer**: High-level API for common operations
- **Expression Caching**: Automatic caching with LRU eviction for optimal performance
- **Error Handling**: Comprehensive error reporting with position information
- **Extensible Design**: Plugin-based function and operator system

## Installation

### Standalone Installation

```bash
composer require ardenexal/fhir-path
```

### With FHIRBundle

```bash
composer require ardenexal/fhir-bundle
```

## Quick Start

### Basic Usage

```php
<?php

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;

// Create service
$service = new FHIRPathService();

// Evaluate expression against FHIR resource
$result = $service->evaluate('Patient.name.given', $patient);

// Get first official name
$result = $service->evaluate('name.where(use = "official").given.first()', $patient);

// Check if patient has phone number
$hasPhone = $service->evaluate('telecom.where(system = "phone").exists()', $patient);
```

### With Compilation and Caching

```php
<?php

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;

$service = new FHIRPathService();

// Compile expression once
$compiled = $service->compile('name.where(use = "official").given.first()');

// Evaluate many times (fast - uses cache)
foreach ($patients as $patient) {
    $result = $compiled->evaluate($patient);
    echo "Name: {$result->single()}\n";
}

// Check cache performance
$stats = $service->getCacheStats();
echo "Cache hits: {$stats['hits']}, misses: {$stats['misses']}\n";
```

## Core Components

### FHIRPathService

Main service for FHIRPath operations with automatic expression caching:

```php
<?php

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;

$service = new FHIRPathService();

// Simple evaluation (automatically cached)
$result = $service->evaluate('Patient.name.given', $patient);

// Validate expression syntax
$isValid = $service->validate('Patient.name.given');

// Compile for explicit reuse
$compiled = $service->compile('name.where(use = "official").given');

// Manage cache
$service->clearCache();
$stats = $service->getCacheStats(); // ['hits' => 10, 'misses' => 2, 'size' => 5]
```

### CompiledExpression

Pre-parsed expressions for optimal performance:

```php
<?php

$compiled = $service->compile('age > 18');

// Evaluate against multiple resources
foreach ($patients as $patient) {
    if ($compiled->evaluate($patient)->single()) {
        echo "{$patient->name} is an adult\n";
    }
}

// Get expression string
echo $compiled->getExpression(); // 'age > 18'
```

### Expression Caching

Automatic caching with LRU eviction for frequently used expressions:

```php
<?php

use Ardenexal\FHIRTools\Component\FHIRPath\Cache\InMemoryExpressionCache;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;

// Custom cache size
$cache = new InMemoryExpressionCache(500); // Cache up to 500 expressions
$service = new FHIRPathService($cache);

// Automatic caching on first use
$result1 = $service->evaluate('name.given', $patient1); // Cache miss - parses expression
$result2 = $service->evaluate('name.given', $patient2); // Cache hit - uses cached AST

// Monitor performance
$stats = $service->getCacheStats();
echo "Hit rate: " . ($stats['hits'] / ($stats['hits'] + $stats['misses']) * 100) . "%\n";
```

### Type System Integration

FHIRPath type operations with FHIR model support:

```php
<?php

use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

$fhirInt = new FHIRInteger(value: 42);

// Type checking with 'is' operator
$result = $service->evaluate('$this is integer', $fhirInt);
// Returns collection containing the integer (type matches)

// Type casting with 'as' operator
$result = $service->evaluate('$this as string', $fhirInt);
// Returns collection containing '42' as string

// Type compatibility
$result = $service->evaluate('$this is decimal', $fhirInt);
// Returns collection (integer is compatible with decimal)

// Filter collections by type
$data = (object)['items' => [$fhirInt, new FHIRString(value: 'text')]];
$result = $service->evaluate('items is integer', $data);
// Returns collection with only the FHIRInteger
```

### Expression Evaluation

Navigate FHIR resources with path expressions:

```php
<?php

// Simple path navigation
$names = $service->evaluate('Patient.name', $patient);

// Filtering with where()
$officialNames = $service->evaluate('name.where(use = "official")', $patient);

// Function chaining
$firstName = $service->evaluate('name.where(use = "official").given.first()', $patient);

// Complex queries
$observations = $service->evaluate(
    'Observation.where(value > 100 and status = "final")',
    $bundle
);
```

### Functions

Over 50 built-in functions across multiple categories:

```php
<?php

// Existence functions
$service->evaluate('name.exists()', $patient);              // Has any name?
$service->evaluate('name.empty()', $patient);               // Has no name?
$service->evaluate('telecom.all(system = "phone")', $patient); // All phones?

// Filtering and projection
$service->evaluate('name.where(use = "official")', $patient);
$service->evaluate('name.select(given)', $patient);
$service->evaluate('name.first()', $patient);

// String functions
$service->evaluate('name.given.upper()', $patient);
$service->evaluate('name.family.substring(0, 3)', $patient);
$service->evaluate('name.given.matches("[A-Z].*")', $patient);

// Math functions
$service->evaluate('Observation.value.sum()', $bundle);
$service->evaluate('Observation.value.avg()', $bundle);
$service->evaluate('(42).abs()', null);

// Type functions
$service->evaluate('value.toInteger()', $observation);
$service->evaluate('value is Quantity', $observation);
```

### Operators

Full operator support with correct precedence:

```php
<?php

// Comparison operators
$service->evaluate('age > 18', $patient);
$service->evaluate('status = "active"', $patient);

// Logical operators
$service->evaluate('age > 18 and status = "active"', $patient);
$service->evaluate('value > 100 or value < 0', $observation);

// Mathematical operators
$service->evaluate('value * 2', $observation);
$service->evaluate('(value + 10) / 2', $observation);

// Collection operators
$service->evaluate('name | telecom', $patient);  // Union
```

## Advanced Usage

### Custom Evaluation Context

```php
<?php

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

$context = new EvaluationContext();
$context->setRootResource($patient);
$context->setVariable('threshold', 100);

$result = $service->evaluateWithContext(
    'Observation.where(value > %threshold)',
    $context
);
```

### Error Handling

```php
<?php

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\ParseException;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

try {
    $result = $service->evaluate('Patient.invalid.path', $patient);
} catch (ParseException $e) {
    echo "Syntax error at line {$e->getLine()}, column {$e->getColumn()}: ";
    echo $e->getMessage();
} catch (EvaluationException $e) {
    echo "Evaluation error: {$e->getMessage()}";
}
```

## Performance

The FHIRPath component is optimized for performance:

- **Simple paths**: < 1ms (e.g., `Patient.name`)
- **Complex queries**: < 10ms (with functions and operators)
- **Large resources**: < 100ms (> 1MB resources)
- **Compilation**: < 5ms (parse + optimize)
- **Cache hits**: < 0.1ms (pre-compiled expressions)

### Performance Tips

1. **Compile frequently-used expressions**:
   ```php
   $compiled = $service->compile('name.where(use = "official").given');
   // Reuse $compiled for many evaluations
   ```

2. **Use lazy evaluation**:
   - FHIRPath automatically uses lazy evaluation
   - Only evaluates what's needed for the result

3. **Cache results when appropriate**:
   ```php
   $cachedResult = $service->evaluate('complex.query()', $resource);
   // Store $cachedResult if resource doesn't change
   ```

## Testing

### Unit Testing

```php
<?php

namespace Tests\FHIRPath;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use PHPUnit\Framework\TestCase;

class FHIRPathServiceTest extends TestCase
{
    private FHIRPathService $service;
    
    protected function setUp(): void
    {
        $this->service = new FHIRPathService();
    }
    
    public function testSimplePathNavigation(): void
    {
        $patient = ['name' => [['given' => ['John']]]];
        
        $result = $this->service->evaluate('name.given', $patient);
        
        self::assertEquals(['John'], $result->toArray());
    }
}
```

## Requirements

- **PHP**: 8.2 or higher
- **Dependencies**:
  - `symfony/string`: ^6.4|^7.4
  - `symfony/property-access`: ^6.4|^7.4

## Documentation

For detailed documentation, see:

- **Component Requirements**: `/docs/component-guides/fhir-path.md`
- **Architecture Overview**: `/docs/architecture-fhirpath.md`
- **Implementation Guide**: `/docs/FHIRPATH_IMPLEMENTATION_GUIDE.md`
- **Language Specification**: `/docs/FHIRPATH_LANGUAGE_SPEC.md`
- **Quick Reference**: `/docs/FHIRPATH_QUICK_REFERENCE.md`

## FHIRPath Specification

This component implements the [FHIRPath 2.0 specification](http://hl7.org/fhirpath/N1/).

Key features:
- Complete grammar support
- All standard functions
- Full operator precedence
- Three-valued logic
- Collection semantics
- Type system with FHIR types

## Contributing

Contributions are welcome! Please ensure:

- Code follows PSR-12 coding standards
- All files use `declare(strict_types=1)`
- Tests have 90%+ coverage
- PHPDoc is complete for all public methods
- Changes don't break existing functionality

## License

This component is released under the MIT License. See the LICENSE file for details.

## Related Components

- **CodeGeneration** (`ardenexal/fhir-code-generation`): Generate FHIR model classes
- **Serialization** (`ardenexal/fhir-serialization`): FHIR JSON serialization
- **FHIRBundle** (`ardenexal/fhir-bundle`): Symfony integration

## Status

**Current Phase**: Phase 10 - Documentation 🚧

Implementation progress:
- [x] Phase 1: Project Setup (Week 1) ✅
- [x] Phase 2: Lexer Implementation (Week 2) ✅
- [x] Phase 3: Parser Implementation (Weeks 3-4) ✅
- [x] Phase 4: Evaluator Implementation (Weeks 5-6) ✅
- [x] Phase 5: Function Library (Weeks 7-9) ✅
- [x] Phase 6: Operator Implementation (Weeks 10-11) ✅
- [x] Phase 7: Type System (Weeks 12-13) ✅
- [x] Phase 8: Service Layer (Week 14) ✅
- [x] Phase 9: Optimization (Week 15) ✅
- [x] Phase 10: Documentation (Week 16) ✅
- [x] Phase 11: Integration (Week 17) ✅
- [x] Phase 12: Final Review (Week 18) ✅

### Recent Achievements

**Phase 12: Final Review (Complete)** ✅
- All 228 tests passing
- PHPStan level 5 analysis clean
- Code style compliant
- Production-ready implementation

**Phase 11: Integration (Complete)** ✅
- Symfony bundle integration
- Console commands (evaluate, validate)
- Dependency injection support
- Configuration management

**Phase 10: Documentation (Complete)** ✅
- Comprehensive API reference
- Performance optimization guide
- Integration examples
- 1,100+ lines of documentation

**Phase 9: Optimization (Complete)** ✅
- Expression caching with LRU eviction
- Automatic cache management
- Performance monitoring (hit rate, statistics)
- Configurable cache size

**Phase 8: Service Layer (Complete)** ✅
- High-level FHIRPathService API
- CompiledExpression for reusable expressions
- Expression validation
- Clean error handling

**Phase 7: Type System (Complete)** ✅
- Type operations (`is`/`as` operators)
- FHIR model integration
- Type inference and compatibility
- Support for FHIR primitives and resources
