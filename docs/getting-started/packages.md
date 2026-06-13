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
| `ardenexal/fhir-serialization` | Read/write FHIR JSON or XML (and validate) |
| `ardenexal/fhir-path` | Evaluate FHIRPath expressions |
| `ardenexal/fhir-models` | Use the pre-generated R4 / R4B / R5 model classes |

{% hint style="info" %}
`ardenexal/fhir-serialization` needs model classes to read and write. Either install
`ardenexal/fhir-models` or generate your own with `ardenexal/fhir-code-generation`.
{% endhint %}

<!-- MIGRATION SOURCE: root README.md (Monorepo Structure table) -->
