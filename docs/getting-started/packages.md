---
description: Pick the right Composer package for your use case.
icon: boxes-stacked
---

# Choosing the Right Package

This is a library monorepo. Each component is published as a standalone Composer package.

| Package | Use it when you need to… |
| --- | --- |
| `ardenexal/fhir-bundle` | Integrate with a Symfony application (wires everything + console commands) |
| `ardenexal/fhir-code-generation` | Generate PHP classes from FHIR definitions / Implementation Guides |
| `ardenexal/fhir-serialization` | Read/write FHIR JSON or XML |
| `ardenexal/fhir-validation` | Validate resources against base and profile constraints |
| `ardenexal/fhir-path` | Evaluate FHIRPath 2.0 expressions |
| `ardenexal/fhir-models` | Use the pre-generated R4 / R4B / R5 model classes |
| `ardenexal/cda-sd-models` | Use the pre-generated CDA R2 and AU CDA logical-model classes (XML only) |
| `ardenexal/fhir-metadata` | Read FHIR attributes, type and property metadata, or the IG type registry (a dependency of the others) |

{% hint style="info" %}
`ardenexal/fhir-serialization` needs model classes to read and write — it already depends on
`ardenexal/fhir-models`, so they come together. Generate your own classes instead with
`ardenexal/fhir-code-generation` when you need versions or Implementation Guides that the
pre-generated models do not cover.
{% endhint %}

{% hint style="info" %}
Validation lives in its own package, `ardenexal/fhir-validation`, not in `ardenexal/fhir-serialization`.
The `ardenexal/fhir-bundle` brings in serialization, validation, FHIRPath, code generation, and
metadata together.
{% endhint %}

{% hint style="info" %}
`ardenexal/cda-sd-models` is always an explicit install — no other package depends on it, including
`ardenexal/fhir-bundle`, so it never arrives transitively. CDA is an XML-only format: these classes
round-trip as XML and the JSON path refuses them. See
[Generating CDA Logical Models](../code-generation/cda.md).
{% endhint %}
