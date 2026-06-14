---
description: Differences between FHIR R4, R4B, and R5 models and how they coexist.
icon: code-branch
---

# FHIR Versions: R4 / R4B / R5

{% hint style="info" %}
This is the **one place** in the docs where FHIR-version differences are documented. The rest of the
library (serialization, FHIRPath, validation) behaves the same across versions — only the model
classes differ.
{% endhint %}

Each FHIR version is generated into its own namespace under
`Ardenexal\FHIRTools\Component\Models\`:

| Version | Namespace root | Core package |
|---------|----------------|--------------|
| R4 | `…\Models\R4\` | `hl7.fhir.r4.core` |
| R4B | `…\Models\R4B\` | `hl7.fhir.r4b.core` |
| R5 | `…\Models\R5\` | `hl7.fhir.r5.core` |

Because the version is a namespace segment, the same resource (e.g. `PatientResource`) exists
independently in each version and the classes never clash. Each namespace carries the identical
subfolder layout (`Resource`, `DataType`, `Primitive`, `Enum`, `Extension`, `Profile`) described
in [Namespace Organization](../models/namespaces.md).

{% tabs %}
{% tab title="R4" %}
```php
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;

$patient = new PatientResource(/* … */);
```
{% endtab %}

{% tab title="R4B" %}
```php
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\PatientResource;

$patient = new PatientResource(/* … */);
```
{% endtab %}

{% tab title="R5" %}
```php
use Ardenexal\FHIRTools\Component\Models\R5\Resource\PatientResource;

$patient = new PatientResource(/* … */);
```
{% endtab %}
{% endtabs %}

## Using multiple versions side by side

Because each version lives in a separate namespace, you can import classes from more than one
version in the same file. Use PHP namespace aliasing to disambiguate same-named classes:

```php
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource as R4Patient;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\PatientResource as R4BPatient;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\PatientResource as R5Patient;

$r4  = new R4Patient(/* … */);
$r5  = new R5Patient(/* … */);
```

{% hint style="info" %}
Models are not committed for every version by default. Generate the versions you need with
`composer run generate-models-r4`, `generate-models-r4b`, `generate-models-r5`, or
`generate-models-all`. See [Generating Implementation Guides](../code-generation/implementation-guides.md)
and the [command reference](../reference/commands.md).
{% endhint %}
