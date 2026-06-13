---
description: Generate typed extension and profile classes for FHIR Implementation Guides.
icon: sitemap
---

# Generating Implementation Guides

Generate typed extension and profile classes for an Implementation Guide (IG), such as US Core or
AU Core, including multi-level profile inheritance chains.

## Command

```bash
php bin/console fhir:generate-ig --package=hl7.fhir.us.core
```

<!-- TODO: migrate "Implementation Guide classes (fhir:generate-ig)" usage from
     src/Component/CodeGeneration/README.md -->

## Profile inheritance

<!-- TODO: explain multi-level inheritance (AU Core → AU Base → R4) and isolated IG namespaces -->

## Core classes

<!-- TODO: migrate FHIRExtensionGenerator, FHIRProfileGenerator -->

{% hint style="info" %}
IG-generated classes live in an isolated namespace tree so they never overlap with the base models.
Serializing them requires an IG-aware service — see [IG-Aware Serialization](../serialization/ig-aware.md).
{% endhint %}

<!-- MIGRATION SOURCE: src/Component/CodeGeneration/README.md (Implementation Guide Generation) -->
