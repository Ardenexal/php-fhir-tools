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

<!-- TODO: migrate the JSON deserialize example from src/Component/Serialization/README.md -->
```php
// $patient = $service->deserializeFromJson($json, FHIRPatient::class);
```
{% endstep %}

{% step %}
### Validate it

<!-- TODO: migrate the validate() example from src/Component/Validation/README.md -->
```php
// $report = $validationService->validate($patient);
```
{% endstep %}

{% step %}
### Query with FHIRPath

<!-- TODO: migrate the evaluate() example from src/Component/FHIRPath/README.md -->
```php
// $given = $fhirPath->evaluate('Patient.name.given', $patient);
```
{% endstep %}
{% endstepper %}

<!-- MIGRATION SOURCE: README.md Quick Start, component READMEs -->
