---
description: Install PHP FHIR Tools as standalone components or via the Symfony bundle.
icon: download
---

# Installation

{% hint style="info" %}
**Requirements:** PHP 8.3+ and Composer. Individual packages may declare additional requirements —
see each component's page.
{% endhint %}

## Standalone components

Install only the packages you need:

{% tabs %}
{% tab title="Serialization" %}
```bash
composer require ardenexal/fhir-serialization ardenexal/fhir-models
```
{% endtab %}

{% tab title="FHIRPath" %}
```bash
composer require ardenexal/fhir-path
```
{% endtab %}

{% tab title="Code Generation" %}
```bash
composer require ardenexal/fhir-code-generation
```
{% endtab %}
{% endtabs %}

## Symfony application

```bash
composer require ardenexal/fhir-bundle
```

The bundle registers all services automatically. See [Installation & Configuration](../bundle/configuration.md)
and the [Flex Recipe](../bundle/flex-recipe.md).

<!-- MIGRATION SOURCE: root README.md (Installation), recipe/fhir-bundle/1.0/INSTALLATION.md -->
<!-- TODO: confirm minimum PHP/Symfony versions per package from composer.json -->
