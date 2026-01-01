# Terminology Resolution Component

This directory contains the terminology resolution infrastructure for FHIR profile validation.

## Overview

The terminology resolution component implements **remote-first, package fallback** strategy as specified in production requirements:

1. **RemoteTerminologyResolver**: Primary resolver using FHIR terminology server
2. **PackageTerminologyResolver**: Fallback resolver using locally loaded FHIR packages
3. **FallbackTerminologyResolver**: Orchestrator implementing remote-first strategy
4. **CircuitBreaker**: Resilience pattern for handling terminology server failures

## Components

### TerminologyResolverInterface

Standard interface for all terminology resolvers:

```php
interface TerminologyResolverInterface
{
    // Validate that a code is a member of a ValueSet
    public function validateCode(
        string $valueSetUrl,
        string $system,
        string $code,
        ?string $display = null,
        ?string $version = null
    ): bool;

    // Expand a ValueSet to get all possible codes
    public function expand(
        string $valueSetUrl,
        ?string $version = null,
        ?int $count = null,
        ?int $offset = null
    ): array;

    // Check if this resolver can handle the given ValueSet
    public function canResolve(string $valueSetUrl): bool;
}
```

### RemoteTerminologyResolver

Connects to FHIR terminology server via HTTP:

**Features**:
- FHIR `$validate-code` and `$expand` operations
- PSR-6 caching with 72-hour TTL
- Circuit breaker integration
- Configurable timeout (default 500ms)
- LRU-bounded cache

**Example**:
```php
use Ardenexal\FHIRTools\Component\Validation\Terminology\RemoteTerminologyResolver;
use Ardenexal\FHIRTools\Component\Validation\Terminology\CircuitBreaker;

$circuitBreaker = new CircuitBreaker($cache);
$resolver = new RemoteTerminologyResolver(
    'https://tx.fhir.org/r4',
    $httpClient,
    $requestFactory,
    $streamFactory,
    $cache,
    $circuitBreaker
);

// Validate a code
$isValid = $resolver->validateCode(
    'http://hl7.org/fhir/ValueSet/administrative-gender',
    'http://hl7.org/fhir/administrative-gender',
    'male'
);

// Expand a ValueSet
$codes = $resolver->expand('http://hl7.org/fhir/ValueSet/administrative-gender');
```

### PackageTerminologyResolver

Uses locally loaded FHIR packages as fallback:

**Features**:
- Loads ValueSets from FHIR packages
- Supports pre-computed expansions
- Simple compose rule evaluation
- No network dependencies

**Limitations**:
- Cannot expand ValueSets with filters
- Relies on pre-computed expansions in packages
- No live terminology server features

**Example**:
```php
use Ardenexal\FHIRTools\Component\Validation\Terminology\PackageTerminologyResolver;

$resolver = new PackageTerminologyResolver($packageLoader);

// Validate code using local package
$isValid = $resolver->validateCode(
    'http://hl7.org/fhir/ValueSet/administrative-gender',
    'http://hl7.org/fhir/administrative-gender',
    'female'
);
```

### FallbackTerminologyResolver

**Recommended for production** - implements remote-first strategy:

**Resolution Strategy**:
1. Try `RemoteTerminologyResolver` first
2. If remote fails or circuit breaker is OPEN, fall back to `PackageTerminologyResolver`
3. If both fail, throw exception

**Example**:
```php
use Ardenexal\FHIRTools\Component\Validation\Terminology\FallbackTerminologyResolver;

$resolver = new FallbackTerminologyResolver(
    $remoteResolver,
    $packageResolver
);

// Automatically tries remote, falls back to package
$isValid = $resolver->validateCode(
    'http://hl7.org/fhir/ValueSet/observation-status',
    'http://hl7.org/fhir/observation-status',
    'final'
);
```

### CircuitBreaker

Implements circuit breaker pattern for resilience:

**States**:
- **CLOSED**: Normal operation (requests allowed)
- **OPEN**: Too many failures (requests blocked)
- **HALF_OPEN**: Testing if service recovered (limited requests)

**Configuration**:
- `failureThreshold`: Open circuit after N failures (default: 5)
- `successThreshold`: Close circuit after N successes in HALF_OPEN (default: 2)
- `timeout`: Seconds before trying HALF_OPEN (default: 60)

**Example**:
```php
use Ardenexal\FHIRTools\Component\Validation\Terminology\CircuitBreaker;

$breaker = new CircuitBreaker(
    $cache,
    failureThreshold: 5,
    successThreshold: 2,
    timeout: 60
);

if ($breaker->isAvailable('terminology_server')) {
    try {
        // Make request
        $result = $terminologyServer->validateCode(...);
        $breaker->recordSuccess('terminology_server');
    } catch (\Exception $e) {
        $breaker->recordFailure('terminology_server');
    }
}
```

## Binding Strength Semantics

FHIR defines four binding strengths for ValueSet bindings:

### 1. Required (`required`)

Code **MUST** be from the ValueSet.

**Validation**: `validateCode()` returns false → **ERROR**

```php
if (!$resolver->validateCode($valueSetUrl, $system, $code)) {
    // Emit error violation
}
```

### 2. Extensible (`extensible`)

Code **SHOULD** be from the ValueSet, but other codes are allowed if no suitable code exists.

**Validation**: `validateCode()` returns false → **WARNING** (configurable)

```php
if (!$resolver->validateCode($valueSetUrl, $system, $code)) {
    // Emit warning violation (or allow based on configuration)
}
```

### 3. Preferred (`preferred`)

Code **SHOULD** be from the ValueSet, but is advisory only.

**Validation**: No validation errors (optional informational message)

### 4. Example (`example`)

ValueSet is for illustration only, not validated.

**Validation**: No validation

## Caching Strategy

### Cache Keys

**validateCode**:
```
validate_code_{hash(valueSetUrl|system|code|version)}
```

**expand**:
```
expand_{hash(valueSetUrl|version)}
```

### TTL (Time To Live)

- **validateCode**: 72 hours (codes rarely change)
- **expand**: 72 hours (expansions rarely change)
- **Circuit Breaker**: 1 hour (state persistence)

### Cache Warming

For production, prewarm common ValueSets on startup:

```php
$commonValueSets = [
    'http://hl7.org/fhir/ValueSet/administrative-gender',
    'http://hl7.org/fhir/ValueSet/observation-status',
    // ...
];

foreach ($commonValueSets as $valueSetUrl) {
    $resolver->expand($valueSetUrl); // Warms cache
}
```

## Performance Targets

- **p95 latency** (warm cache): <5ms per `validateCode()`
- **p95 latency** (cold cache, remote): <200ms per `validateCode()`
- **p95 latency** (package fallback): <50ms per `validateCode()`

## Error Handling

All terminology resolution errors should be handled gracefully:

```php
try {
    $isValid = $resolver->validateCode($valueSetUrl, $system, $code);
} catch (\RuntimeException $e) {
    // Log error
    // Emit validation warning
    // Continue validation (don't fail entire validation)
}
```

## Integration with Validation

Terminology resolution is used during profile validation for:

1. **ValueSet Binding Validation**: Check if coded values match binding requirements
2. **Slice Discriminator Matching**: Validate discriminator values against ValueSets
3. **Code Normalization**: Expand ValueSets for comprehensive validation

**Example Integration**:

```php
// In profile validator
foreach ($element['binding'] as $binding) {
    $strength = $binding['strength']; // required, extensible, preferred, example
    $valueSetUrl = $binding['valueSet'];
    
    if ($strength === 'required' || $strength === 'extensible') {
        $coding = $elementValue; // Extract from resource
        
        $isValid = $terminologyResolver->validateCode(
            $valueSetUrl,
            $coding['system'],
            $coding['code']
        );
        
        if (!$isValid) {
            if ($strength === 'required') {
                // Emit ERROR violation
            } else {
                // Emit WARNING violation (extensible)
            }
        }
    }
}
```

## Testing

Unit tests cover:

- Remote resolver success and failure scenarios
- Package resolver with various ValueSet structures
- Fallback resolver strategy (remote → package)
- Circuit breaker state transitions
- Cache hit/miss behavior
- Binding strength semantics

## Production Considerations

1. **Circuit Breaker**: Configure thresholds based on terminology server SLA
2. **Cache Limits**: Set LRU limits to prevent unbounded growth
3. **Timeouts**: Adjust based on network latency (200-500ms recommended)
4. **Monitoring**: Track circuit breaker state, cache hit rate, latency
5. **Prewarm**: Load common ValueSets on application startup

## See Also

- [FHIR Terminology Service](https://www.hl7.org/fhir/terminology-service.html)
- [FHIR ValueSet Resource](https://www.hl7.org/fhir/valueset.html)
- [Circuit Breaker Pattern](https://martinfowler.com/bliki/CircuitBreaker.html)
