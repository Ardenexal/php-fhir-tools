# FHIRPath API Reference

Complete API reference for the FHIRPath component.

## Table of Contents

- [Service Layer](#service-layer)
  - [FHIRPathService](#fhirpathservice)
  - [CompiledExpression](#compiledexpression)
- [Caching](#caching)
  - [ExpressionCacheInterface](#expressioncacheinterface)
  - [InMemoryExpressionCache](#inmemoryexpressioncache)
- [Type System](#type-system)
  - [FHIRTypeResolver](#fhirtyperesolver)
- [Core Components](#core-components)
  - [Collection](#collection)
  - [EvaluationContext](#evaluationcontext)

## Service Layer

### FHIRPathService

High-level service for FHIRPath expression evaluation with automatic caching.

#### Constructor

```php
public function __construct(?ExpressionCacheInterface $cache = null)
```

**Parameters:**
- `$cache` - Optional custom cache implementation (defaults to InMemoryExpressionCache with 100 item limit)

**Example:**
```php
// Default cache
$service = new FHIRPathService();

// Custom cache
$cache = new InMemoryExpressionCache(500);
$service = new FHIRPathService($cache);
```

#### evaluate()

Evaluate a FHIRPath expression against a resource.

```php
public function evaluate(
    string $expression, 
    mixed $resource, 
    ?EvaluationContext $context = null
): Collection
```

**Parameters:**
- `$expression` - FHIRPath expression string
- `$resource` - FHIR resource or data to evaluate against
- `$context` - Optional evaluation context (for variables, etc.)

**Returns:** Collection of results

**Throws:** FHIRPathException if expression is invalid or evaluation fails

**Example:**
```php
$result = $service->evaluate('Patient.name.given', $patient);
$names = $result->toArray();
```

#### validate()

Validate expression syntax without evaluating it.

```php
public function validate(string $expression): bool
```

**Parameters:**
- `$expression` - FHIRPath expression to validate

**Returns:** true if valid, false otherwise

**Example:**
```php
if ($service->validate('Patient.name.given')) {
    echo "Valid expression\n";
}
```

#### compile()

Compile an expression for repeated evaluation.

```php
public function compile(string $expression): CompiledExpression
```

**Parameters:**
- `$expression` - FHIRPath expression to compile

**Returns:** CompiledExpression object

**Throws:** FHIRPathException if expression is invalid

**Example:**
```php
$compiled = $service->compile('name.given.first()');
foreach ($patients as $patient) {
    $name = $compiled->evaluate($patient);
}
```

#### getCacheStats()

Get cache performance statistics.

```php
public function getCacheStats(): array
```

**Returns:** Array with keys:
- `hits` (int) - Number of cache hits
- `misses` (int) - Number of cache misses
- `size` (int) - Current cache size

**Example:**
```php
$stats = $service->getCacheStats();
$hitRate = $stats['hits'] / ($stats['hits'] + $stats['misses']) * 100;
echo "Cache hit rate: {$hitRate}%\n";
```

#### clearCache()

Clear all cached expressions.

```php
public function clearCache(): void
```

**Example:**
```php
$service->clearCache();
```

#### getCache()

Get the cache instance.

```php
public function getCache(): ExpressionCacheInterface
```

**Returns:** The cache implementation in use

---

### CompiledExpression

Represents a pre-compiled FHIRPath expression.

#### evaluate()

Evaluate the compiled expression against a resource.

```php
public function evaluate(
    mixed $resource, 
    ?EvaluationContext $context = null
): Collection
```

**Parameters:**
- `$resource` - FHIR resource or data to evaluate against
- `$context` - Optional evaluation context

**Returns:** Collection of results

**Throws:** EvaluationException if evaluation fails

**Example:**
```php
$compiled = $service->compile('age > 18');
$result = $compiled->evaluate($patient);
```

#### getExpression()

Get the original expression string.

```php
public function getExpression(): string
```

**Returns:** The expression string

**Example:**
```php
echo $compiled->getExpression(); // 'age > 18'
```

#### getAst()

Get the parsed AST (Abstract Syntax Tree).

```php
public function getAst(): ExpressionNode
```

**Returns:** The root expression node

---

## Caching

### ExpressionCacheInterface

Interface for expression cache implementations.

#### has()

Check if an expression is cached.

```php
public function has(string $expression): bool
```

#### get()

Get a cached compiled expression.

```php
public function get(string $expression): ?CompiledExpression
```

#### set()

Store a compiled expression in cache.

```php
public function set(string $expression, CompiledExpression $compiled): void
```

#### delete()

Remove an expression from cache.

```php
public function delete(string $expression): void
```

#### clear()

Clear all cached expressions.

```php
public function clear(): void
```

#### getStats()

Get cache statistics.

```php
public function getStats(): array
```

---

### InMemoryExpressionCache

In-memory cache with LRU (Least Recently Used) eviction.

#### Constructor

```php
public function __construct(int $maxSize = 100)
```

**Parameters:**
- `$maxSize` - Maximum number of expressions to cache (default: 100)

**Example:**
```php
$cache = new InMemoryExpressionCache(500);
```

#### getHitRate()

Get cache hit rate as percentage.

```php
public function getHitRate(): float
```

**Returns:** Hit rate (0-100)

**Example:**
```php
$cache = $service->getCache();
if ($cache instanceof InMemoryExpressionCache) {
    echo "Hit rate: " . $cache->getHitRate() . "%\n";
}
```

#### getMaxSize()

Get maximum cache size.

```php
public function getMaxSize(): int
```

**Returns:** Maximum number of cached expressions

---

## Type System

### FHIRTypeResolver

Resolves and manages FHIR types for type operations.

#### inferType()

Infer FHIR type from a PHP value.

```php
public function inferType(mixed $value): string
```

**Parameters:**
- `$value` - Value to infer type from

**Returns:** FHIR type name (e.g., 'integer', 'string', 'boolean')

**Example:**
```php
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger;

$resolver = new FHIRTypeResolver();
$type = $resolver->inferType(new FHIRInteger(value: 42)); // 'integer'
```

#### isOfType()

Check if a value is of a specific FHIR type.

```php
public function isOfType(mixed $value, string $typeName): bool
```

**Parameters:**
- `$value` - Value to check
- `$typeName` - FHIR type name to check against

**Returns:** true if value is of the specified type

**Example:**
```php
$isInteger = $resolver->isOfType($fhirInt, 'integer'); // true
$isDecimal = $resolver->isOfType($fhirInt, 'decimal'); // true (compatible)
```

#### castToType()

Cast a value to a specific FHIR type.

```php
public function castToType(mixed $value, string $typeName): mixed
```

**Parameters:**
- `$value` - Value to cast
- `$typeName` - Target FHIR type name

**Returns:** Casted value

**Throws:** InvalidArgumentException if cast fails

**Example:**
```php
$stringValue = $resolver->castToType($fhirInt, 'string'); // '42'
```

---

## Core Components

### Collection

Represents a collection of values (FHIRPath result set).

#### from()

Create collection from array.

```php
public static function from(array $items): self
```

#### empty()

Create empty collection.

```php
public static function empty(): self
```

#### toArray()

Convert to PHP array.

```php
public function toArray(): array
```

#### count()

Get number of items.

```php
public function count(): int
```

#### first()

Get first item.

```php
public function first(): mixed
```

#### last()

Get last item.

```php
public function last(): mixed
```

#### single()

Get single item (throws if not exactly one item).

```php
public function single(): mixed
```

#### isEmpty()

Check if collection is empty.

```php
public function isEmpty(): bool
```

---

### EvaluationContext

Context for expression evaluation (variables, resources, etc.).

#### setVariable()

Set a variable for use in expressions.

```php
public function setVariable(string $name, mixed $value): void
```

**Example:**
```php
$context = new EvaluationContext();
$context->setVariable('threshold', 100);
$result = $service->evaluate('value > %threshold', $observation, $context);
```

#### getVariable()

Get a variable value.

```php
public function getVariable(string $name): mixed
```

#### setRootResource()

Set the root resource.

```php
public function setRootResource(mixed $resource): void
```

## Usage Examples

### Basic Evaluation

```php
$service = new FHIRPathService();

// Simple path
$names = $service->evaluate('Patient.name', $patient);

// With filtering
$official = $service->evaluate('name.where(use = "official")', $patient);

// With function
$first = $service->evaluate('name.given.first()', $patient);
```

### Compilation and Reuse

```php
$compiled = $service->compile('telecom.where(system = "phone").value');

foreach ($patients as $patient) {
    $phones = $compiled->evaluate($patient);
    foreach ($phones as $phone) {
        echo "Phone: $phone\n";
    }
}
```

### Type Operations

```php
// Type checking
$result = $service->evaluate('value is Quantity', $observation);

// Type casting
$result = $service->evaluate('value as string', $observation);

// With FHIR models
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger;
$fhirInt = new FHIRInteger(value: 42);
$result = $service->evaluate('$this is integer', $fhirInt); // matches
```

### Cache Management

```php
// Custom cache size
$cache = new InMemoryExpressionCache(1000);
$service = new FHIRPathService($cache);

// Use service...
$service->evaluate('expression1', $resource);
$service->evaluate('expression2', $resource);

// Monitor performance
$stats = $service->getCacheStats();
echo "Cached expressions: {$stats['size']}\n";
echo "Cache hits: {$stats['hits']}\n";
echo "Cache misses: {$stats['misses']}\n";

// Clear if needed
$service->clearCache();
```

### Custom Context

```php
$context = new EvaluationContext();
$context->setVariable('minAge', 18);
$context->setVariable('maxAge', 65);

$result = $service->evaluate(
    'age >= %minAge and age <= %maxAge',
    $patient,
    $context
);
```
