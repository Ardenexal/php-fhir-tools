---
description: How generated models are organized into namespaces per FHIR version.
icon: folder-tree
---

# Namespace Organization

Generated classes live under the root namespace
`Ardenexal\FHIRTools\Component\Models\` and are grouped per FHIR version (`R4`, `R4B`, `R5`).
Each version namespace carries the same set of subfolders:

| Subfolder | Namespace segment | Contents | Example class |
|-----------|-------------------|----------|---------------|
| `Resource` | `…\R4\Resource` | FHIR resources | `PatientResource` |
| `DataType` | `…\R4\DataType` | Complex data types | `HumanName`, `Address` |
| `Primitive` | `…\R4\Primitive` | Version-specific primitives | `StringPrimitive`, `BooleanPrimitive` |
| `Enum` | `…\R4\Enum` | Required ValueSet enums | `AdministrativeGender` |
| `Extension` | `…\R4\Extension` | Named extensions defined by the core spec | `ADUseExtension` |
| `Profile` | `…\R4\Profile` | Constraint profiles defined by the core spec | `ActualGroupProfile` |

A small set of shared primitives lives **outside** the version folders, directly under
`Ardenexal\FHIRTools\Component\Models\Primitive` (for example `FHIRDate`, `FHIRDateTime`,
`FHIRInstant`, `FHIRTime`). These are version-agnostic and reused across R4/R4B/R5.

```php
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\AdministrativeGender;
```

## Backbone elements

Backbone elements are nested in a sub-namespace named after their parent resource, so they
never collide with same-named backbones on other resources:

```
R4/Resource/
├── PatientResource.php          → …\R4\Resource\PatientResource
└── Patient/
    ├── PatientContact.php        → …\R4\Resource\Patient\PatientContact
    ├── PatientCommunication.php  → …\R4\Resource\Patient\PatientCommunication
    └── PatientLink.php           → …\R4\Resource\Patient\PatientLink
```

```php
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Patient\PatientContact;
```

{% hint style="danger" %}
Everything under `src/Component/Models/src/` is generated output. Never hand-edit it —
regenerate with `fhir:generate`. See [Generated Output Structure](../code-generation/output-structure.md).
{% endhint %}

Implementation Guide classes are generated into a separate `IG/` tree, not these version
folders — see [Generating Implementation Guides](../code-generation/implementation-guides.md).
