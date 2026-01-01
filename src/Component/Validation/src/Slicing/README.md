# FHIR Slicing Component

## Overview

The Slicing component implements FHIR's discriminator-based slicing with cardinality enforcement. It efficiently matches array elements to slices and validates slice constraints.

## Features

- **Discriminator Types**: value, pattern, type, profile, exists
- **Slice Matching**: First-match-wins bucketing
- **Cardinality Validation**: Per-slice min/max enforcement
- **Slicing Rules**: open, closed, openAtEnd
- **Two-Level Caching**: In-memory + PSR-6 for compiled matchers
- **Lazy Compilation**: Matchers compiled on-demand

## Usage

### Basic Slice Matching

```php
use Ardenexal\FHIRTools\Component\Validation\Slicing\Slicer;

$slicer = new Slicer($cache);

// Define slicing from StructureDefinition snapshot
$slicingDefinition = [
    'discriminator' => [
        ['type' => 'value', 'path' => 'coding.system']
    ],
    'rules' => 'open', // open | closed | openAtEnd
    'slices' => [
        'loincSlice' => [
            'discriminatorValue' => [
                'coding.system' => 'http://loinc.org'
            ],
            'min' => 1,
            'max' => '1'
        ],
        'snomedSlice' => [
            'discriminatorValue' => [
                'coding.system' => 'http://snomed.info/sct'
            ],
            'min' => 0,
            'max' => '*'
        ]
    ]
];

// Match elements to slices
$elements = [
    ['coding' => ['system' => 'http://loinc.org', 'code' => '1234']],
    ['coding' => ['system' => 'http://snomed.info/sct', 'code' => '5678']],
    ['coding' => ['system' => 'http://example.org', 'code' => '9999']]
];

$buckets = $slicer->match(
    $elements,
    $slicingDefinition,
    'http://hl7.org/fhir/StructureDefinition/Observation',
    'Observation.code.coding'
);

// Result:
// [
//     'loincSlice' => [element1],
//     'snomedSlice' => [element2],
//     '__unmatched' => [element3]
// ]
```

### Cardinality Validation

```php
// Validate cardinality constraints
$issues = $slicer->validateCardinality($buckets, $slicingDefinition);

// Issues:
// [
//     ['slice' => 'loincSlice', 'violation' => 'min_cardinality', 'min' => 1, 'actual' => 0]
// ]
```

## Discriminator Types

### Value Discriminator

Matches exact value at path:

```php
$slicingDefinition = [
    'discriminator' => [
        ['type' => 'value', 'path' => 'use']
    ],
    'slices' => [
        'officialSlice' => [
            'discriminatorValue' => ['use' => 'official']
        ]
    ]
];
```

### Pattern Discriminator

Matches pattern (partial object match):

```php
$slicingDefinition = [
    'discriminator' => [
        ['type' => 'pattern', 'path' => 'coding']
    ],
    'slices' => [
        'loincSlice' => [
            'discriminatorPattern' => [
                'coding' => ['system' => 'http://loinc.org']
            ]
        ]
    ]
];
```

### Type Discriminator

Matches element type (for choice types):

```php
$slicingDefinition = [
    'discriminator' => [
        ['type' => 'type', 'path' => 'value[x]']
    ],
    'slices' => [
        'stringSlice' => [
            'discriminatorType' => ['value[x]' => 'string']
        ]
    ]
];
```

### Profile Discriminator

Matches meta.profile[]:

```php
$slicingDefinition = [
    'discriminator' => [
        ['type' => 'profile', 'path' => '$this']
    ],
    'slices' => [
        'usCoreSlice' => [
            'discriminatorProfile' => [
                '$this' => 'http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient'
            ]
        ]
    ]
];
```

### Exists Discriminator

Matches presence/absence:

```php
$slicingDefinition = [
    'discriminator' => [
        ['type' => 'exists', 'path' => 'identifier']
    ],
    'slices' => [
        'withIdSlice' => [
            'discriminatorExists' => ['identifier' => true]
        ]
    ]
];
```

## Slicing Rules

### Open Slicing

Unmatched elements are allowed:

```php
$slicingDefinition = [
    'rules' => 'open',
    'slices' => [...]
];

// __unmatched bucket will be removed if empty
```

### Closed Slicing

Unmatched elements are violations:

```php
$slicingDefinition = [
    'rules' => 'closed',
    'slices' => [...]
];

// __unmatched bucket will cause validation failure
$issues = $slicer->validateCardinality($buckets, $slicingDefinition);
// [['slice' => '__unmatched', 'violation' => 'closed_slicing', 'actual' => 2]]
```

### OpenAtEnd Slicing

Additional elements allowed after defined slices:

```php
$slicingDefinition = [
    'rules' => 'openAtEnd',
    'slices' => [...]
];
```

## Performance

- **Two-Level Caching**: In-memory + PSR-6 (24h TTL)
- **Lazy Compilation**: Matchers created on-demand
- **Matcher Reuse**: Same matcher for all elements of same slice

### Cache Management

```php
// Check if cached
$isCached = $slicer->isCached($profileUrl, $elementPath, $sliceName);

// Clear cache
$slicer->clearCache(); // All
$slicer->clearCache($profileUrl); // Profile-specific (currently clears all)
```

## Integration with Validation

```php
// In validator:
$selectorRegistry = new SelectorRegistry($cache);
$slicer = new Slicer($cache);

// Get array elements
$selector = $selectorRegistry->getSelector($profileUrl, $elementPath);
$elements = $selector($resource);

// Match to slices
$buckets = $slicer->match($elements, $slicingDefinition, $profileUrl, $elementPath);

// Validate cardinality
$issues = $slicer->validateCardinality($buckets, $slicingDefinition);

// Create violations
foreach ($issues as $issue) {
    $violations[] = new ConstraintViolation(
        sprintf('Slice "%s" violation: %s', $issue['slice'], $issue['violation']),
        null,
        [],
        $resource,
        $elementPath,
        null,
        null,
        'fhir.slice.' . $issue['violation']
    );
}
```

## Why This Matters

Slicing is **CRITICAL** for profile validation because:

- ✅ Enables complex profile constraints (e.g., "must have LOINC and SNOMED codings")
- ✅ Supports discriminator-based matching (5 types)
- ✅ Enforces per-slice cardinality
- ✅ Handles open/closed slicing rules
- ✅ Cached matchers provide performance
- ✅ Required for US Core and other implementation guides

## Testing

See `tests/Unit/Component/Validation/Slicing/SlicerTest.php` for comprehensive test coverage including:

- Value, pattern, type, profile, exists discriminators
- Cardinality validation (min/max)
- Open/closed slicing rules
- Cache hit/miss scenarios
- Multiple discriminators (AND logic)
