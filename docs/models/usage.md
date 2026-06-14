---
description: Instantiate and work with the generated FHIR model classes.
icon: code
---

# Using Generated Models

Generated resources are plain PHP objects with a promoted-property constructor, so you build them
with named arguments. Complex types, primitives, and enums each have their own classes — see
[Namespace Organization](namespaces.md).

```php
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Enum\AdministrativeGender;

$patient = new PatientResource(
    name: [
        new HumanName(
            family: new StringPrimitive(value: 'Doe'),
            given: [new StringPrimitive(value: 'John')],
        ),
    ],
);
```

## Accessing properties

Properties are public, so you read them directly. Primitive-typed properties are wrapped objects
exposing a `->value`:

```php
echo $patient->name[0]->family->value; // 'Doe'
```

{% hint style="info" %}
Some scalar properties (such as `id`) are plain PHP types (`?string`), while others (such as
`family`) are primitive wrapper objects (`StringPrimitive`) so they can carry FHIR extensions. Check
the generated class to see which a given property uses.
{% endhint %}

## Enums

Coded value sets are generated as PHP enums:

```php
$gender = AdministrativeGender::Male;
$cases  = AdministrativeGender::cases(); // all enum values
```

## Next steps

* [Serialization](../serialization/overview.md) — read and write these objects as JSON or XML.
* [Validation](../validation/overview.md) — check them against the specification and profiles.
