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
| `ardenexal/fhir-sdc` | Run SDC `$populate` (pre-fill a `QuestionnaireResponse` from launch context) and `$extract` (turn a completed `QuestionnaireResponse` into a transaction Bundle of FHIR resources) |
| `ardenexal/fhir-models` | Use the pre-generated R4 / R4B / R5 model classes |
| `ardenexal/fhir-metadata` | Shared FHIR attributes and interfaces (a dependency of the others) |

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
