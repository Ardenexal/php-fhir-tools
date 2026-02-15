# Performance Guidelines

## Current Implementation

### FHIR Package Processing
The CodeGeneration component processes FHIR packages by:
1. Loading FHIR package definitions from cache or downloading
2. Parsing all StructureDefinitions
3. Generating PHP classes using Nette PhpGenerator
4. Writing files to the output directory

### Caching
- **Package Cache**: FHIR packages cached in `var/cache/fhir-packages/`
- **Cache Integrity**: `CacheIntegrityManager` verifies package integrity
- **Version Isolation**: `VersionIsolationManager` handles multi-version support

### Progress Reporting
- Uses Symfony Console `ProgressIndicator` for visual feedback during generation
- Provides verbose output with `-vvv` flag

## Memory Considerations

### PHP Configuration
Set appropriate memory limits for large FHIR packages:
```php
ini_set('memory_limit', '-1'); // For very large packages
```

### File Operations
- Generated files written directly to disk
- Atomic file writes using temporary files when appropriate

## Network Operations

### Package Downloads
- `PackageLoader` handles FHIR package downloads
- `RetryHandler` implements retry logic with exponential backoff
- HTTPS used for all package registry requests

### Timeout Configuration
Set appropriate timeouts for network operations to prevent hanging.

## Profiling

### Execution Time
- Commands provide timing information with verbose output
- PHPStan configured with parallel processing (4 processes)

### Testing Performance
```bash
# Run tests with timing
composer test -- --testdox
```

## Quality Commands

```bash
# Full quality check
composer quality:all       # lint + phpstan + test

# Component-specific quality
composer quality:bundle
composer quality:codegen
composer quality:serialization
```
