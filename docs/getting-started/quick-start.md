---
description: A first end-to-end example — serialize, validate, and query a FHIR resource.
icon: bolt
---

# Quick Start

This page walks through a minimal end-to-end flow.

{% stepper %}
{% step %}
### Install

Follow [Installation](installation.md) for your setup (standalone or Symfony bundle).
{% endstep %}

{% step %}
### Deserialize a resource

Build a serialization service and read a FHIR JSON document into a typed model. Outside of Symfony,
`FHIRSerializationService::createDefault()` wires the service for you (pass a `FhirVersion` to target
R4B or R5).

```php
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;

$service = FHIRSerializationService::createDefault();

$json = file_get_contents('patient.json');
$patient = $service->deserializeFromJson($json, PatientResource::class);

// Round-trip back to JSON (or use serializeToXml() for XML):
$roundTripped = $service->serializeToJson($patient);
```

{% hint style="info" %}
`deserialize()` can auto-detect both the format (JSON or XML) and the target class from the
payload's `resourceType`, so `$service->deserialize($data)` works when you do not know the type
ahead of time.
{% endhint %}
{% endstep %}

{% step %}
### Validate it

`FHIRValidationService::validate()` returns an `FHIRValidationReport`. Pass profile canonical URLs
to also evaluate profile constraints; with none, only base constraints run.

```php
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;

// $validationService is autowired from the container (see the note below)
/** @var FHIRValidationService $validationService */
$report = $validationService->validate($patient);

if (!$report->isValid()) {
    foreach ($report->errors() as $violation) {
        // inspect error-severity violations
    }
}
```

{% hint style="info" %}
`isValid()` is true when there are no error-severity violations; warnings and info do not affect it.
`FHIRValidationService` depends on Symfony's `ValidatorInterface` and `FHIRPathService`, so inject it
via the [Symfony bundle](../bundle/configuration.md) rather than constructing it by hand.
{% endhint %}
{% endstep %}

{% step %}
### Query with FHIRPath

`FHIRPathService::evaluate()` returns a `Collection`; call `toArray()` or `first()` to read results.

```php
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;

$fhirPath = new FHIRPathService();

$given = $fhirPath->evaluate('Patient.name.given', $patient);

foreach ($given->toArray() as $value) {
    // each given name
}
```
{% endstep %}
{% endstepper %}
