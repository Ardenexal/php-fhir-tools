---
description: Pre-compile and cache FHIRPath expressions for repeated evaluation.
icon: gauge-high
---

# Compilation, Caching & Performance

Parsing a FHIRPath expression into an AST is the costly step. To avoid repeating it, expressions
can be pre-compiled once and evaluated many times, and the service caches compiled expressions
automatically.

## Compilation

`compile()` parses an expression and returns a `CompiledExpression`
(`Ardenexal\FHIRTools\Component\FHIRPath\Service\CompiledExpression`) holding the pre-parsed AST.
Evaluating it skips lexing and parsing entirely:

```php
// Compile once, evaluate many times
$compiled = $service->compile('name.where(use = "official").given.first()');

foreach ($patients as $patient) {
    $result = $compiled->evaluate($patient);
}
```

A `CompiledExpression` also exposes `getExpression()`, `getAst()`, and `__toString()`.

## Caching

`evaluate()` and `compile()` both route through an internal cache, so even without calling
`compile()` explicitly, repeated evaluation of the same expression string reuses the parsed AST.

The default cache is `InMemoryExpressionCache`
(`Ardenexal\FHIRTools\Component\FHIRPath\Cache\InMemoryExpressionCache`), which keys compiled
expressions by their source string.

{% hint style="info" %}
The cache holds up to **100 expressions by default** and evicts the **least recently used (LRU)**
entry when full. Construct the cache with a different `maxSize` to tune this.
{% endhint %}

### Custom cache

Inject any `ExpressionCacheInterface` implementation via the constructor:

```php
use Ardenexal\FHIRTools\Component\FHIRPath\Cache\InMemoryExpressionCache;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;

$service = new FHIRPathService(new InMemoryExpressionCache(maxSize: 500));
```

### Cache statistics and control

```php
$stats = $service->getCacheStats();
// ['hits' => 10, 'misses' => 2, 'size' => 5]

$service->clearCache();          // empty the cache and reset counters
$cache = $service->getCache();   // the underlying ExpressionCacheInterface
```

The in-memory cache additionally exposes `getHitRate()` (0–100) and `getMaxSize()`.

## Tips

* Compile expressions used in hot loops once, outside the loop.
* Reuse a single `FHIRPathService` instance so its cache survives across calls.
* For large batches that exceed the default 100-entry cache, raise `maxSize` to keep all
  expressions resident and avoid re-parsing on eviction.
