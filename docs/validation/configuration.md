---
description: Configure terminology clients, reference resolvers, and obligation contexts.
icon: gear
---

# Configuration

Several validation concerns rely on pluggable services you configure:

## Terminology clients

* `HttpFHIRTerminologyClient` — calls an external terminology server
* `CachingFHIRTerminologyClient` — caches terminology results
* `NullFHIRTerminologyClient` — disables terminology calls

<!-- TODO: migrate terminology client configuration -->

## Reference resolvers

`FHIRReferenceResolverInterface` / `NullFHIRReferenceResolver`.

<!-- TODO: migrate reference resolver configuration -->

## Type hierarchy resolvers

`FHIRTypeHierarchyResolverInterface` and implementations.

<!-- TODO: migrate type hierarchy resolver configuration -->

## Obligation contexts

`FHIRObligationContext` — actor-scoped obligation enforcement.

<!-- MIGRATION SOURCE: src/Component/Validation/README.md (Configuration Guide,
     terminology/reference/type-hierarchy/obligation interfaces) -->
