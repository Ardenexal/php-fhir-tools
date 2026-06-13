---
description: Pre-compile and cache FHIRPath expressions for repeated evaluation.
icon: gauge-high
---

# Compilation, Caching & Performance

<!-- TODO: migrate "Compilation and Caching" from src/Component/FHIRPath/README.md -->

Expressions can be pre-compiled and cached (LRU eviction) so repeated evaluation avoids re-parsing.

```php
// $compiled = $fhirPath->compile('Patient.name.given');
```

<!-- MIGRATION SOURCE: src/Component/FHIRPath/README.md (Compilation and Caching) -->
