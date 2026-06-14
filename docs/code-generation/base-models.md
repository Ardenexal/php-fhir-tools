---
description: Generate the base FHIR model classes with fhir:generate.
icon: cube
---

# Generating Base FHIR Models

Generate PHP classes for a base FHIR package (e.g. `hl7.fhir.r4.core`). This is the canonical model
layer — Resources, DataTypes, Primitives, and Enums — that everything else (serialization,
validation, IG profiles) builds on.

## Command

```bash
php demo/bin/console fhir:generate --package=hl7.fhir.r4.core -vvv
```

{% hint style="info" %}
The console lives at `demo/bin/console` (a small demo Symfony app that wires up the bundle). The
composer scripts below call the same binary.
{% endhint %}

### Options

| Option | Description |
|--------|-------------|
| `--package` | Package(s) to generate, as `name` or `name#version`. Repeatable. Defaults to the R4 core set when omitted. |
| `--offline` | Use only locally cached packages — no network access. |

The matching **terminology** package (e.g. `hl7.terminology.r4`) is detected from the FHIR version of
the packages you request and prepended automatically, so shared CodeSystems and ValueSets (such as
`AdministrativeGender`) resolve correctly.

{% tabs %}
{% tab title="R4" %}
```bash
php demo/bin/console fhir:generate --package=hl7.fhir.r4.core --package=hl7.fhir.uv.extensions.r4 -vvv
```
{% endtab %}

{% tab title="R4B" %}
```bash
php demo/bin/console fhir:generate --package=hl7.fhir.r4b.core --package=hl7.fhir.uv.extensions.r4b -vvv
```
{% endtab %}

{% tab title="R5" %}
```bash
php demo/bin/console fhir:generate --package=hl7.fhir.r5.core --package=hl7.fhir.uv.extensions.r5 -vvv
```
{% endtab %}
{% endtabs %}

You can pass packages for several versions in one invocation; only the affected version directories
are cleared and regenerated.

## Composer scripts

```bash
composer run generate-models-r4    # R4 core + R4 extensions
composer run generate-models-r4b   # R4B core + R4B extensions
composer run generate-models-r5    # R5 core + R5 extensions
composer run generate-models-all   # R4 + R4B + R5
composer run generate-models       # fhir:generate with default (R4) packages
```

## Core classes

The command orchestrates these generators (all under
`Ardenexal\FHIRTools\Component\CodeGeneration\`):

### `FHIRModelGenerator`

Generates a PHP class for a single base `StructureDefinition`.

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRModelGenerator;

$generator = new FHIRModelGenerator();

// Returns a Nette ClassLike (throws on failure):
$class = $generator->generate($structureDefinition, $version, $builderContext);

// Or collect errors instead of throwing (used by the command), returns ?ClassType:
$class = $generator->generateModelClassWithErrorHandling(
    $structureDefinition,
    $version,
    $errorCollector,
    $builderContext,
);
```

### `FHIRValueSetGenerator`

Generates a PHP enum from a `ValueSet` definition.

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRValueSetGenerator;

$generator = new FHIRValueSetGenerator();
$enumType  = $generator->generateEnum($valueSetDefinition, $version, $builderContext);
```

### `PackageLoader`

Manages FHIR package downloads, caching, and extraction.

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;

$metadata    = $loader->installPackage('hl7.fhir.r4.core', '4.0.1');
$definitions = $loader->loadPackageStructureDefinitions($metadata);

$cached = $loader->isPackageCached('hl7.fhir.r4.core', '4.0.1');
```

### `BuilderContext`

Holds state during a run — loaded definitions, generated classes, pending enums, and per-version
namespace registrations.

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;

$context = new BuilderContext();
$context->loadDefinitions($definitions);
$info = $context->getType('http://hl7.org/fhir/StructureDefinition/Patient');
```

## Error handling

Fatal failures throw `PackageException` (download / extraction / integrity) or `GenerationException`
(code generation). Non-fatal issues are gathered by `ErrorCollector` and printed at the end of the
run; with `-vvv` you also get per-definition detail.

```php
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException;

try {
    $metadata = $loader->installPackage('hl7.fhir.r4.core');
} catch (PackageException $e) {
    // download, extraction, or integrity failure
} catch (GenerationException $e) {
    // code generation failure
}
```

Output lands under `src/Component/Models/src/{version}/` — see
[Generated Output Structure](output-structure.md).
