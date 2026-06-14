---
description: Serialize and deserialize FHIR resources as XML, with XXE protection.
icon: code
---

# XML Serialization

XML serialization mirrors the JSON API. The XML root element name is set to the FHIR resource type
(e.g. `Patient`), and the FHIR namespace `http://hl7.org/fhir` is emitted by default.

```php
<?php

use Ardenexal\FHIRTools\Component\Models\R4B\Resource\PatientResource;

$patient = new PatientResource(id: 'example-123', active: true);

$xml = $serializer->serializeToXml($patient);
// <?xml version="1.0"?>
// <Patient xmlns="http://hl7.org/fhir"><id value="example-123"/><active value="true"/></Patient>

$restored = $serializer->deserializeFromXml($xml, PatientResource::class);
echo $restored->id; // "example-123"
```

The signatures are `serializeToXml(object $fhirObject, array $context = []): string` and
`deserializeFromXml(string $xmlData, string $targetClass, array $context = []): object`. See
[Context & Options](context.md) for the shared options.

{% hint style="warning" %}
**XXE protection.** XML deserialization strips `DOCTYPE` declarations
(`XmlEncoder::DECODER_IGNORED_NODE_TYPES` set to `XML_DOCUMENT_TYPE_NODE`) so external entity
definitions are never processed. Attribute values are also preserved as strings
(`TYPE_CAST_ATTRIBUTES` disabled) so numeric-looking primitives such as `"1.0"` or `"2002"` keep
their precision on round-trip rather than being cast to float/int.
{% endhint %}

## Error handling

Like JSON, all failures are wrapped in `FHIRSerializationException`:

```php
<?php

use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;

try {
    $patient = $serializer->deserializeFromXml($maybeInvalidXml, PatientResource::class);
} catch (FHIRSerializationException $e) {
    error_log('FHIR XML deserialization failed: ' . $e->getMessage());
}
```
