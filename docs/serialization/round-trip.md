---
description: Verify that serialize → deserialize round-trips preserve a resource.
icon: rotate
---

# Round-Trip Testing

A round-trip assertion — serialize an object, deserialize it back, and check the result equals the
original — is the most useful unit test for the serializer. There is no built-in `roundTripTest()`
helper on the service; you write the two calls explicitly, which keeps the assertion close to the
data you care about.

```php
<?php

namespace App\Tests\Serialization;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\HumanName;
use PHPUnit\Framework\TestCase;

final class PatientRoundTripTest extends TestCase
{
    public function testJsonRoundTripPreservesData(): void
    {
        $serializer = FHIRSerializationService::createDefault();

        $patient = new PatientResource(
            id: 'rt-1',
            name: [new HumanName(family: 'Smith', given: ['Jane', 'Q'])],
        );

        $json     = $serializer->serializeToJson($patient);
        $restored = $serializer->deserializeFromJson($json, PatientResource::class);

        // `id` stays a plain string; primitive sub-fields return as Stringable objects, so cast.
        self::assertSame('rt-1', $restored->id);
        self::assertSame('Smith', (string) $restored->name[0]->family);
        self::assertSame(['Jane', 'Q'], array_map('strval', $restored->name[0]->given));
    }
}
```

{% hint style="info" %}
**Compare primitives with care.** Top-level resource fields like `id` deserialize as plain strings,
but typed primitive sub-fields come back as `Stringable` primitive objects. Cast with `(string)` (or
`array_map('strval', ...)` for arrays) before a `assertSame`. See [JSON Serialization](json.md) for
detail.
{% endhint %}

{% hint style="warning" %}
**XML single-element repeating fields.** When round-tripping through XML, a repeating field holding
exactly one value does not currently survive deserialization (XML collapses the lone element to a
scalar). Use two-or-more values in XML round-trip fixtures, or test single values through JSON. See
[XML Serialization](xml.md).
{% endhint %}

## In a Symfony test

Fetch the configured service from the container instead of constructing it:

```php
<?php

self::bootKernel();
$serializer = static::getContainer()->get('fhir.serialization_service');
```

See also [Contributing → Testing](../contributing/testing.md).
