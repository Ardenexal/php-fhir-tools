# FHIRPath Performance and Optimization Guide

Learn how to optimize FHIRPath expression evaluation for maximum performance.

## Table of Contents

- [Expression Caching](#expression-caching)
- [Compilation Best Practices](#compilation-best-practices)
- [Performance Tips](#performance-tips)
- [Benchmarking](#benchmarking)
- [Common Pitfalls](#common-pitfalls)

## Expression Caching

The FHIRPath service automatically caches compiled expressions to avoid re-parsing.

### How Caching Works

1. **First Evaluation**: Expression is parsed and compiled, then cached
2. **Subsequent Evaluations**: Cached compiled expression is reused
3. **LRU Eviction**: Least recently used expressions are evicted when cache is full

### Cache Configuration

```php
use Ardenexal\FHIRTools\Component\FHIRPath\Cache\InMemoryExpressionCache;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;

// Default cache (100 expressions)
$service = new FHIRPathService();

// Custom cache size
$cache = new InMemoryExpressionCache(500);
$service = new FHIRPathService($cache);

// For high-volume applications
$cache = new InMemoryExpressionCache(2000);
$service = new FHIRPathService($cache);
```

### Cache Size Guidelines

| Use Case | Recommended Size | Reason |
|----------|-----------------|---------|
| Simple applications | 50-100 | Few unique expressions |
| Web applications | 200-500 | Multiple user queries |
| High-volume APIs | 1000-2000 | Many unique expressions |
| Batch processing | 100-200 | Repeated expressions |

### Monitoring Cache Performance

```php
$stats = $service->getCacheStats();

echo "Cache size: {$stats['size']}\n";
echo "Cache hits: {$stats['hits']}\n";
echo "Cache misses: {$stats['misses']}\n";

// Calculate hit rate
$total = $stats['hits'] + $stats['misses'];
if ($total > 0) {
    $hitRate = ($stats['hits'] / $total) * 100;
    echo "Hit rate: " . number_format($hitRate, 2) . "%\n";
}
```

**Good Hit Rate**: Above 80% indicates effective caching
**Low Hit Rate**: Below 50% may indicate cache is too small or expressions are too diverse

## Compilation Best Practices

### When to Use compile()

**Use compile() when:**
- Evaluating the same expression multiple times
- Processing batches of resources
- Expression is known at startup time
- Building reusable query objects

```php
// Good: Compile once, use many times
$compiled = $service->compile('name.where(use = "official").given.first()');

foreach ($patients as $patient) {
    $name = $compiled->evaluate($patient);
    echo "Name: {$name->single()}\n";
}
```

**Don't use compile() when:**
- Expression is used only once
- Expression is dynamic/user-generated
- Caching is already handling it

```php
// Unnecessary: Service already caches automatically
foreach ($patients as $patient) {
    // This is fine - automatic caching handles it
    $name = $service->evaluate('name.given.first()', $patient);
}
```

### Pre-compilation Pattern

For known expressions at startup:

```php
class PatientQueryService
{
    private CompiledExpression $officialNameQuery;
    private CompiledExpression $phoneQuery;
    private CompiledExpression $emailQuery;
    
    public function __construct(FHIRPathService $service)
    {
        // Pre-compile common queries
        $this->officialNameQuery = $service->compile('name.where(use = "official").given.first()');
        $this->phoneQuery = $service->compile('telecom.where(system = "phone").value');
        $this->emailQuery = $service->compile('telecom.where(system = "email").value');
    }
    
    public function getOfficialName(object $patient): ?string
    {
        return $this->officialNameQuery->evaluate($patient)->single();
    }
    
    public function getPhones(object $patient): array
    {
        return $this->phoneQuery->evaluate($patient)->toArray();
    }
}
```

## Performance Tips

### 1. Use Specific Paths

**Avoid:**
```php
// Searches entire resource tree
$service->evaluate('descendants().where(code = "12345")', $resource);
```

**Prefer:**
```php
// Direct path is much faster
$service->evaluate('code.coding.where(code = "12345")', $resource);
```

### 2. Filter Early

**Avoid:**
```php
// Processes all observations first, then filters
$service->evaluate('Observation.select(value).where(value > 100)', $bundle);
```

**Prefer:**
```php
// Filters first, reducing data to process
$service->evaluate('Observation.where(value > 100).value', $bundle);
```

### 3. Use exists() for Boolean Checks

**Avoid:**
```php
// Returns full collection, then checks count
if ($service->evaluate('telecom.where(system = "phone")', $patient)->count() > 0) {
    // ...
}
```

**Prefer:**
```php
// Stops at first match
if ($service->evaluate('telecom.where(system = "phone").exists()', $patient)->single()) {
    // ...
}
```

### 4. Minimize Function Calls

**Avoid:**
```php
// Multiple function calls in loop
foreach ($patients as $patient) {
    $upper = $service->evaluate('name.given.upper()', $patient);
    $lower = $service->evaluate('name.given.lower()', $patient);
}
```

**Prefer:**
```php
// Single evaluation, PHP processing
foreach ($patients as $patient) {
    $given = $service->evaluate('name.given', $patient)->first();
    $upper = strtoupper($given);
    $lower = strtolower($given);
}
```

### 5. Batch Resource Processing

**Avoid:**
```php
// Separate evaluations
foreach ($patients as $patient) {
    $name = $service->evaluate('name', $patient);
    $phone = $service->evaluate('telecom.where(system = "phone")', $patient);
    $email = $service->evaluate('telecom.where(system = "email")', $patient);
}
```

**Prefer:**
```php
// Pre-compile queries
$nameQuery = $service->compile('name');
$phoneQuery = $service->compile('telecom.where(system = "phone")');
$emailQuery = $service->compile('telecom.where(system = "email")');

foreach ($patients as $patient) {
    $name = $nameQuery->evaluate($patient);
    $phone = $phoneQuery->evaluate($patient);
    $email = $emailQuery->evaluate($patient);
}
```

## Benchmarking

### Measuring Expression Performance

```php
function benchmarkExpression(FHIRPathService $service, string $expression, $resource, int $iterations = 1000): array
{
    // Warm up cache
    $service->evaluate($expression, $resource);
    
    $start = microtime(true);
    for ($i = 0; $i < $iterations; $i++) {
        $service->evaluate($expression, $resource);
    }
    $end = microtime(true);
    
    $totalTime = $end - $start;
    $avgTime = ($totalTime / $iterations) * 1000; // milliseconds
    
    return [
        'total_time' => $totalTime,
        'avg_time_ms' => $avgTime,
        'iterations' => $iterations,
    ];
}

// Usage
$result = benchmarkExpression($service, 'Patient.name.given', $patient, 10000);
echo "Average time: " . number_format($result['avg_time_ms'], 3) . " ms\n";
```

### Comparing Approaches

```php
// Compare cached vs uncached
$service1 = new FHIRPathService(); // With cache
$service2 = new FHIRPathService(new class implements ExpressionCacheInterface {
    // Null cache for comparison
    public function has(string $expression): bool { return false; }
    public function get(string $expression): ?CompiledExpression { return null; }
    public function set(string $expression, CompiledExpression $compiled): void {}
    public function delete(string $expression): void {}
    public function clear(): void {}
    public function getStats(): array { return ['hits' => 0, 'misses' => 0, 'size' => 0]; }
});

$expression = 'name.where(use = "official").given.first()';
$iterations = 1000;

$cached = benchmarkExpression($service1, $expression, $patient, $iterations);
$uncached = benchmarkExpression($service2, $expression, $patient, $iterations);

echo "Cached: " . number_format($cached['avg_time_ms'], 3) . " ms\n";
echo "Uncached: " . number_format($uncached['avg_time_ms'], 3) . " ms\n";
echo "Speedup: " . number_format($uncached['avg_time_ms'] / $cached['avg_time_ms'], 1) . "x\n";
```

### Expected Performance

Typical performance on modern hardware:

| Operation | Time (without cache) | Time (with cache) |
|-----------|---------------------|-------------------|
| Simple path (`name.given`) | 0.5-1 ms | 0.05-0.1 ms |
| Complex query | 2-5 ms | 0.1-0.3 ms |
| With functions | 3-10 ms | 0.2-0.5 ms |

## Common Pitfalls

### 1. Not Reusing Service Instance

**Avoid:**
```php
foreach ($patients as $patient) {
    $service = new FHIRPathService(); // Creates new cache each time!
    $name = $service->evaluate('name.given', $patient);
}
```

**Fix:**
```php
$service = new FHIRPathService(); // Reuse instance
foreach ($patients as $patient) {
    $name = $service->evaluate('name.given', $patient);
}
```

### 2. Cache Too Small

**Symptom**: Low hit rate despite repeated expressions

```php
$stats = $service->getCacheStats();
// hits: 50, misses: 450, size: 100
// Hit rate: 10% - cache is too small!
```

**Fix:**
```php
$cache = new InMemoryExpressionCache(500); // Increase size
$service = new FHIRPathService($cache);
```

### 3. Overly Complex Expressions

**Avoid:**
```php
// Hard to cache, slow to parse
$service->evaluate('Bundle.entry.resource.where($this is Observation).where(code.coding.where(system = "http://loinc.org").code = "12345").where(value > 100).select(value.value)', $bundle);
```

**Fix:**
```php
// Break into steps or use simpler paths
$observations = $service->evaluate('Bundle.entry.resource.where($this is Observation)', $bundle);
// Then filter in PHP or use multiple simpler queries
```

### 4. Unnecessary Validation

**Avoid:**
```php
foreach ($expressions as $expr) {
    if ($service->validate($expr)) { // Validates = parses!
        $result = $service->evaluate($expr, $resource); // Parses again!
    }
}
```

**Fix:**
```php
foreach ($expressions as $expr) {
    try {
        $result = $service->evaluate($expr, $resource); // Just evaluate
    } catch (FHIRPathException $e) {
        // Handle invalid expression
    }
}
```

## Memory Considerations

### Cache Memory Usage

Approximate memory per cached expression: 5-20 KB

| Cache Size | Approximate Memory |
|------------|-------------------|
| 100 | 0.5-2 MB |
| 500 | 2.5-10 MB |
| 1000 | 5-20 MB |
| 2000 | 10-40 MB |

### When to Clear Cache

```php
// After processing large batch
function processBatch(array $resources, FHIRPathService $service): void
{
    foreach ($resources as $resource) {
        // Process...
    }
    
    // Free memory if cache grew large
    $stats = $service->getCacheStats();
    if ($stats['size'] > 1000) {
        $service->clearCache();
    }
}
```

## Production Recommendations

1. **Monitor cache performance** in production
2. **Tune cache size** based on actual usage patterns
3. **Pre-compile** frequently used expressions
4. **Use simple paths** when possible
5. **Profile** slow expressions
6. **Consider memory** vs performance tradeoffs
7. **Log cache statistics** periodically

### Example Monitoring

```php
// Log cache stats every 1000 requests
class FHIRPathMonitor
{
    private int $requestCount = 0;
    
    public function logStatsIfNeeded(FHIRPathService $service): void
    {
        $this->requestCount++;
        
        if ($this->requestCount % 1000 === 0) {
            $stats = $service->getCacheStats();
            $total = $stats['hits'] + $stats['misses'];
            $hitRate = $total > 0 ? ($stats['hits'] / $total) * 100 : 0;
            
            error_log(sprintf(
                'FHIRPath cache: size=%d, hits=%d, misses=%d, hit_rate=%.2f%%',
                $stats['size'],
                $stats['hits'],
                $stats['misses'],
                $hitRate
            ));
        }
    }
}
```
