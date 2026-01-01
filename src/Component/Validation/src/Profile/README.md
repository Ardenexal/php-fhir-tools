# Profile Selection Component

## Overview

The ProfileSelection component resolves which FHIR profiles to validate a resource against, following a clear priority order that supports explicit configuration, resource metadata, and system defaults.

## Profile Selection Priority

ProfileSelection implements a three-tier resolution strategy:

1. **Explicit Profiles** (highest priority)
   - Profiles explicitly requested by the caller
   - Used when validation is triggered programmatically

2. **Resource Meta Profiles** (fallback)
   - Profiles declared in `resource.meta.profile[]`
   - Allows resources to self-declare conformance

3. **Default Profiles** (last resort)
   - Configured defaults per resource type
   - System-wide validation baseline

## Usage

### Basic Usage

```php
use Ardenexal\FHIRTools\Component\Validation\Profile\ProfileSelection;

$selector = new ProfileSelection();

// Explicit profiles take precedence
$profiles = $selector->resolve($patient, [
    'http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient'
]);

// Falls back to meta.profile[] if no explicit profiles
$patient = [
    'resourceType' => 'Patient',
    'meta' => [
        'profile' => [
            'http://example.org/StructureDefinition/MyPatient'
        ]
    ]
];
$profiles = $selector->resolve($patient);
// Returns: ['http://example.org/StructureDefinition/MyPatient']
```

### With Default Profiles

```php
$selector = new ProfileSelection([
    'Patient' => 'http://hl7.org/fhir/StructureDefinition/Patient',
    'Observation' => [
        'http://hl7.org/fhir/StructureDefinition/Observation',
        'http://example.org/StructureDefinition/CustomObservation'
    ]
]);

// Uses default when no explicit or meta profiles
$patient = ['resourceType' => 'Patient'];
$profiles = $selector->resolve($patient);
// Returns: ['http://hl7.org/fhir/StructureDefinition/Patient']
```

### Checking Profile Availability

```php
// Check if resource has meta.profile[]
if ($selector->hasMetaProfiles($resource)) {
    // Resource self-declares conformance
}

// Check if any profiles will be selected
if ($selector->hasProfiles($resource, $explicitProfiles)) {
    // Validation can proceed
}

// Get defaults for a resource type
$defaults = $selector->getDefaultProfilesForType('Patient');
```

## Intersection Semantics

ProfileSelection supports **intersection semantics** where a resource must satisfy **ALL** selected profiles:

```php
// Resource must conform to BOTH profiles
$profiles = $selector->resolve($patient, [
    'http://hl7.org/fhir/StructureDefinition/Patient',
    'http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient'
]);
// Validation will check against both profiles
```

## Configuration

### Symfony Configuration

```yaml
# config/packages/fhir.yaml
fhir:
    validation:
        default_profiles:
            Patient: 'http://hl7.org/fhir/StructureDefinition/Patient'
            Observation:
                - 'http://hl7.org/fhir/StructureDefinition/Observation'
                - 'http://example.org/StructureDefinition/CustomObservation'
```

### Service Registration

```yaml
# services.yaml
services:
    fhir.profile_selection:
        class: Ardenexal\FHIRTools\Component\Validation\Profile\ProfileSelection
        arguments:
            $defaultProfiles: '%fhir.validation.default_profiles%'
```

## Features

- **Three-tier Priority**: Explicit → Meta → Default
- **Deduplication**: Automatically removes duplicate profile URLs
- **Type Safety**: Filters out invalid profile values (empty strings, non-strings)
- **Flexible Configuration**: Supports single or multiple defaults per resource type
- **Intersection Semantics**: Returns all applicable profiles for validation

## Implementation Details

### Profile Resolution Algorithm

1. **Explicit Check**: If `$explicitProfiles` is non-null and non-empty, return deduplicated explicit profiles
2. **Meta Check**: Extract `resource.meta.profile[]`, filter invalid values, return if non-empty
3. **Default Check**: Look up `defaultProfiles[$resourceType]`, return if configured
4. **No Profiles**: Return empty array (validation may be skipped or error)

### Edge Cases Handled

- **Empty Explicit Array**: Treated as "no explicit profiles", falls back to meta/default
- **Non-Array Meta Profile**: Ignored, falls back to defaults
- **Empty Strings**: Filtered out from meta.profile[]
- **Duplicate Profiles**: Automatically deduplicated
- **Missing Resource Type**: Returns empty array

## Testing

```bash
composer test -- tests/Unit/Component/Validation/Profile/
```

**Coverage**:
- 13 test cases
- 22 assertions
- 100% code coverage

## Integration

ProfileSelection integrates with:

- **ValidationService**: Primary profile resolution
- **FHIRProfileValidator**: Determines which profiles to validate
- **Configuration**: Symfony configuration for defaults
- **Meta Extraction**: FHIR resource metadata

## References

- [FHIR Profiling](http://hl7.org/fhir/profiling.html)
- [Meta Element](http://hl7.org/fhir/resource.html#Meta)
- [Profile Declarations](http://hl7.org/fhir/profiling.html#profile-uses)
