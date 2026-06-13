---
description: Validate codes against ValueSet bindings (required, extensible, preferred).
icon: list-check
---

# Terminology & Binding Validation

Validates coded values against their ValueSet bindings using `FHIRValueSetBindingValidator`. Binding
strength (required / extensible / preferred) determines how strictly codes are checked.

{% hint style="info" %}
Terminology checks may call an external terminology server. Configure the client (and result
caching) on the [Configuration](configuration.md) page.
{% endhint %}

<!-- TODO: migrate binding strength behavior + terminology client overview from
     src/Component/Validation/README.md -->

<!-- MIGRATION SOURCE: src/Component/Validation/README.md (binding validation, terminology client) -->
