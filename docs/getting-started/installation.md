---
description: Install PHP FHIR Tools as standalone components or via the Symfony bundle.
icon: download
---

# Installation

{% hint style="info" %}
**Requirements:** PHP 8.3 or newer and [Composer](https://getcomposer.org/). Packages that integrate
with Symfony accept Symfony `^6.4 | ^7.4 | ^8.1`; Symfony 8 itself needs PHP 8.4 or newer, and 8.0 is
not supported. Code generation additionally needs the `zip` extension.
{% endhint %}

## Standalone components

Install only the packages you need. Each is published independently and shares the
`Ardenexal\FHIRTools\Component\…` namespace.

{% tabs %}
{% tab title="Serialization" %}
```bash
composer require ardenexal/fhir-serialization
```

`ardenexal/fhir-serialization` already depends on `ardenexal/fhir-models` (the pre-generated R4 /
R4B / R5 model classes) and `ardenexal/fhir-metadata`, so they are pulled in automatically.
{% endtab %}

{% tab title="Validation" %}
```bash
composer require ardenexal/fhir-validation
```

Provides the Symfony Validator constraints and `FHIRValidationService`. It depends on
`ardenexal/fhir-models` and `ardenexal/fhir-path`.
{% endtab %}

{% tab title="FHIRPath" %}
```bash
composer require ardenexal/fhir-path
```

Provides the FHIRPath 2.0 engine (`FHIRPathService`). Depends only on `ardenexal/fhir-metadata`,
not on the model classes.
{% endtab %}

{% tab title="Code Generation" %}
```bash
composer require ardenexal/fhir-code-generation
```

Generates model classes from FHIR packages. Needs the `zip` extension and pulls in
`nette/php-generator` for code emission.
{% endtab %}

{% tab title="SDC" %}
```bash
composer require ardenexal/fhir-sdc
```

Provides SDC `Questionnaire/$populate` and `QuestionnaireResponse/$extract`. It depends on
`ardenexal/fhir-validation`, `ardenexal/fhir-path` and `ardenexal/fhir-http-client`. See
[SDC](../sdc/overview.md).
{% endtab %}

{% tab title="HTTP Client" %}
```bash
composer require ardenexal/fhir-http-client symfony/http-client
```

Provides `FHIRHttpClient` for calling a FHIR server, OAuth decorators, the x-fhir-query template
resolver, and the HTTP terminology clients that validation can use. It depends on
`ardenexal/fhir-serialization`, `ardenexal/fhir-models`, `ardenexal/fhir-path` and
`ardenexal/fhir-metadata`, and only on the Symfony HTTP client contracts, so install an implementation
such as `symfony/http-client` alongside it. See
[FHIR HTTP Client](../http-client/overview.md).
{% endtab %}
{% endtabs %}

## Symfony application

```bash
composer require ardenexal/fhir-bundle
```

The bundle pulls in the code-generation, serialization, validation, FHIRPath, and metadata
components and registers all of their services automatically. With Symfony Flex it also registers
the bundle, copies the `config/packages/fhir.yaml` configuration, seeds `.env` variables
(`FHIR_OUTPUT_DIRECTORY`, `FHIR_CACHE_DIRECTORY`, `FHIR_DEFAULT_VERSION`,
`FHIR_VALIDATION_ENABLED`, `FHIR_VALIDATION_STRICT_MODE`), and updates `.gitignore`.

{% hint style="info" %}
Without Symfony Flex, register the bundle manually in `config/bundles.php` and copy the
configuration files yourself. See [Installation & Configuration](../bundle/configuration.md)
and the [Flex Recipe](../bundle/flex-recipe.md) for the full manual steps.
{% endhint %}
