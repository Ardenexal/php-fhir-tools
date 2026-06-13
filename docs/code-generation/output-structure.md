---
description: How generated classes are laid out and why they must not be hand-edited.
icon: folder-tree
---

# Generated Output Structure

<!-- TODO: migrate "Output structure" and "Generated class examples" from
     src/Component/CodeGeneration/README.md -->

{% hint style="danger" %}
Files under `src/Component/Models/src/` are generated output. Never hand-edit them — regenerate via
`fhir:generate`. Hand edits are lost on the next generation run.
{% endhint %}

See also [Namespace Organization](../models/namespaces.md) for how the output is structured per FHIR
version.

<!-- MIGRATION SOURCE: src/Component/CodeGeneration/README.md (Output structure, Generated class examples) -->
