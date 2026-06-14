---
description: Generate typed extension and profile classes for FHIR Implementation Guides.
icon: sitemap
---

# Generating Implementation Guides

Generate typed extension and profile classes for an Implementation Guide (IG), such as US Core or
AU Core, including multi-level profile inheritance chains.

The `fhir:generate-ig` command targets the IG-specific layer: StructureDefinitions with
`derivation: constraint` that are intentionally skipped by the base `fhir:generate` pipeline.

## Command

```bash
php bin/console fhir:generate-ig --package=hl7.fhir.us.core
```

For the full option list, version pinning syntax, and offline mode, see the
[command reference](../reference/commands.md#fhir-generate-ig). IG packages can also be configured
declaratively under `ig.packages` — see the
[configuration reference](../reference/configuration.md#ig).

## What gets generated

The command produces two kinds of class:

| Source StructureDefinition | Generated class | Generator |
|----------------------------|-----------------|-----------|
| `type: Extension`, `derivation: constraint` | Typed subclass of `Extension` with the URL baked in and `value[x]` / sub-extensions narrowed to concrete types | `FHIRExtensionGenerator` |
| `kind: resource`/`complex-type`, `derivation: constraint` (non-extension) | Thin subclass of the base resource/type with a `PROFILE_URL` constant and `#[FHIRProfile]` attribute | `FHIRProfileGenerator` |

### Generated extension

```php
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-birthPlace', fhirVersion: 'R4')]
class PatientBirthPlaceExtension extends Extension
{
    public function __construct(
        public ?Address $valueAddress = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(id: $id, extension: $extension, url: '...', value: $this->valueAddress);
    }
}
```

`FHIRExtensionGenerator` detects simple vs. complex extensions automatically: simple extensions
narrow the `value[x]` type to a concrete PHP type, complex extensions map sub-extension slices to
typed properties.

### Generated profile

```php
#[FHIRProfile(profileUrl: 'http://hl7.org.au/fhir/core/StructureDefinition/au-core-patient', baseType: 'Patient', fhirVersion: 'R4')]
class AUCorePatientProfile extends AUBasePatientProfile
{
    public const string PROFILE_URL = 'http://hl7.org.au/fhir/core/StructureDefinition/au-core-patient';
}
```

## Profile inheritance

`FHIRProfileGenerator` resolves each profile's parent from the `BuilderContext`. If the
StructureDefinition's `baseDefinition` URL resolves to an IG profile already registered (rather
than a base FHIR resource), the generated class extends that profile instead — enabling
multi-level chains:

```
PatientResource
└── AUBasePatientProfile      (hl7.fhir.au.base)
      └── AUCorePatientProfile  (hl7.fhir.au.core)
```

Specify packages in dependency order so each package's classes are registered before the
packages that extend them:

```bash
php bin/console fhir:generate-ig --package=hl7.fhir.au.base#1.0.0 --package=hl7.fhir.au.core#1.0.0
```

## Isolated output namespace

IG classes are written to a separate `IG/{version}/{slug}/` tree that never overlaps the base
models. The `slug` (e.g. `AuCore`, `AuBase`) becomes the namespace segment under `IG/{version}/`:

```
Models/src/
├── R4/               ← canonical base types (fhir:generate)
└── IG/
    └── R4/
        ├── AuBase/
        │   ├── Extension/   ← AU Base extensions
        │   └── Profile/     ← AU Base profiles (e.g. AUBasePatientProfile)
        └── AuCore/
            ├── Extension/
            └── Profile/     ← AUCorePatientProfile extends AUBasePatientProfile
```

{% hint style="info" %}
IG-generated classes live in an isolated namespace tree so they never overlap with the base models.
Serializing them requires an IG-aware service — see [IG-Aware Serialization](../serialization/ig-aware.md).
{% endhint %}

See also [Generated Output Structure](output-structure.md).
