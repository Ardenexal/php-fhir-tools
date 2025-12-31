# Snapshot Generation Component

## Overview

The SnapshotGenerator creates complete StructureDefinition snapshots by merging differential elements with base definitions. This is critical for accurate FHIR profile validation.

## Why Snapshots Are Required

Validating against raw differentials leads to incorrect results because:

1. **Missing Inherited Constraints**: Differentials only show changes, not inherited constraints from baseDefinition
2. **Incomplete Cardinality**: Min/max values need to be resolved through the inheritance chain
3. **Unmaterialized Slicing**: Slicing structures need to be fully expanded
4. **Unstable Paths**: Element paths need to be stabilized across the hierarchy

## Usage

```php
use Ardenexal\FHIRTools\Component\Validation\Snapshot\SnapshotGenerator;

$generator = new SnapshotGenerator($packageLoader, $cache);

// Generate snapshot for a StructureDefinition
$snapshotted = $generator->generate($structureDefinition);

// Now validate against the snapshot, not the differential
foreach ($snapshotted['snapshot']['element'] as $element) {
    // Validate with complete, inherited constraints
}
```

## Features

- **Automatic BaseDefinition Resolution**: Recursively loads and merges base definitions
- **Caching**: PSR-6 cache integration with 24-hour TTL
- **Constraint Merging**: Properly merges cardinality, types, bindings, and constraints
- **Binding Strengthening**: Handles binding strength upgrades correctly
- **FHIR Version Detection**: Automatically determines which package to load

## Implementation Details

### Merging Rules

- **Cardinality (min/max)**: More restrictive values win
- **Types**: Differential types constrain base types
- **Bindings**: Stronger binding strength wins
- **Constraints**: Accumulated from base and differential
- **Slicing**: Defined in differential

### Cache Keys

Format: `sd_snapshot:<md5(url|version)>`

TTL: 24 hours

### Supported FHIR Versions

- R4 (4.0.1) - hl7.fhir.r4.core
- R4B (4.3.0) - hl7.fhir.r4b.core  
- R5 (5.0.0) - hl7.fhir.r5.core

## Testing

```bash
composer test -- tests/Unit/Component/Validation/Snapshot/
```

## References

- [FHIR StructureDefinition](http://hl7.org/fhir/structuredefinition.html)
- [FHIR Profiling](http://hl7.org/fhir/profiling.html)
- [Snapshot Generation Algorithm](http://hl7.org/fhir/structuredefinition-definitions.html#StructureDefinition.snapshot)
