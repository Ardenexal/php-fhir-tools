---
description: Serialize and deserialize FHIR resources as XML, with XXE protection.
icon: code
---

# XML Serialization

<!-- TODO: migrate "Serialize and deserialize XML" from docs/component-guides/serialization.md -->

```php
// $xml = $service->serializeToXml($patient);
// $patient = $service->deserializeFromXml($xml, FHIRPatient::class);
```

{% hint style="warning" %}
XML deserialization is hardened against XXE. <!-- TODO: document the protection behavior -->
{% endhint %}

<!-- MIGRATION SOURCE: serialization README/guide (XML sections) -->
