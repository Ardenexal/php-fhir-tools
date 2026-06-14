---
description: How generated classes are laid out and why they must not be hand-edited.
icon: folder-tree
---

# Generated Output Structure

All generated code is written under `src/Component/Models/src/` and rooted at the namespace
`Ardenexal\FHIRTools\Component\Models\`. Two pipelines write into this tree:

- [`fhir:generate`](../reference/commands.md#fhir-generate) — the canonical base FHIR types, one
  folder per version (`R4`, `R4B`, `R5`).
- [`fhir:generate-ig`](../reference/commands.md#fhir-generate-ig) — IG-specific extensions and
  profiles, under an isolated `IG/{version}/{slug}/` tree.

```
Models/src/
├── Primitive/        ← version-agnostic primitives (FHIRDate, FHIRDateTime, …)
├── R4/               ← canonical base types (fhir:generate)
│   ├── Resource/     ← resources + nested backbone folders (e.g. Patient/)
│   ├── DataType/     ← complex data types
│   ├── Primitive/    ← version-specific primitives
│   ├── Enum/         ← required ValueSet enums
│   ├── Extension/    ← named extensions from the core spec
│   └── Profile/      ← constraint profiles from the core spec
├── R4B/              ← same structure as R4
├── R5/               ← same structure as R4
└── IG/               ← Implementation Guide output (fhir:generate-ig)
    └── R4/
        └── AuCore/
            ├── Extension/
            └── Profile/
```

See [Namespace Organization](../models/namespaces.md) for the namespace mapping and backbone
nesting, and [Generating Implementation Guides](implementation-guides.md) for the `IG/` tree.

## Generated class examples

A resource and its nested backbone element:

```php
namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;
// PatientResource.php

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Patient;
// PatientContact.php, PatientCommunication.php, PatientLink.php
```

Classes are produced with [Nette PhpGenerator](https://github.com/nette/php-generator) from the
parsed StructureDefinitions and ValueSets in each FHIR package.

{% hint style="danger" %}
Files under `src/Component/Models/src/` are generated output. Never hand-edit them — regenerate via
`fhir:generate`. Hand edits are lost on the next generation run.
{% endhint %}
