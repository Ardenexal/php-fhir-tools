---
description: Validate that references point to resources conforming to required target profiles.
icon: link
---

# Reference & Target Profile Validation

Validates that references resolve to resources conforming to the required target profiles, via
`FHIRTargetProfileValidator`.

{% hint style="info" %}
Reference resolution is pluggable. Configure a resolver on the [Configuration](configuration.md) page.
{% endhint %}

<!-- TODO: migrate target profile + reference resolution details from
     src/Component/Validation/README.md -->

<!-- MIGRATION SOURCE: src/Component/Validation/README.md (target profile reference validation,
     FHIRReferenceResolverInterface) -->
