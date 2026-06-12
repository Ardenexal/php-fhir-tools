# FHIRBundle Component Guide

## Overview

The FHIRBundle integrates the FHIR Tools components into a Symfony application. It registers the
serialization service, the validation service, the code-generation services, and the FHIRPath
console commands, and exposes a `fhir` configuration tree for tuning them.

## Installation

### Via Composer

```bash
composer require ardenexal/fhir-bundle
```

With Symfony Flex the bundle is registered automatically. Otherwise register it manually in
`config/bundles.php`:

```php
<?php

return [
    // ... other bundles
    Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle::class => ['all' => true],
];
```

> The bundle class is `Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle` — note there is **no**
> `\src` segment in the namespace, even though the file lives under `src/`.

## Configuration

All configuration lives under the `fhir` root key, typically in `config/packages/fhir.yaml`. The
tree below lists every supported key with its default; all keys are optional.

```yaml
fhir:
    # Where generated FHIR classes are written (used by fhir:generate)
    output_directory: '%kernel.project_dir%/output'

    # Cache directory for FHIR package downloads and metadata
    cache_directory: '%kernel.cache_dir%/fhir'

    # Default FHIR version: R4, R4B, or R5
    default_version: 'R4B'

    validation:
        enabled: true            # enable FHIR validation during code generation
        strict_mode: false       # fail on warnings, not just errors
        message_overrides: []    # map of simple constraint class name => message template
        terminology_cache_pool: null   # PSR-6 cache pool service id for terminology results
        terminology_cache_ttl: 3600    # TTL in seconds for cached terminology results (0 = no expiry)

    serialization:
        metadata_cache_pool: 'cache.app'   # PSR-6 pool for property metadata; null disables caching
        enable_cache_warmer: false         # pre-populate metadata cache during cache:warmup

    path:
        cache_size: 100          # max number of compiled FHIRPath expressions to cache (10–10000)

    # FHIR core packages to make available, keyed by package name
    packages:
        hl7.fhir.r4b.core:
            version: '4.3.0'
            # url: 'https://...'   # optional custom package URL
            # auto_update: false   # auto-update to the latest version

    # Implementation Guide generation (see fhir:generate-ig below)
    ig:
        packages: []
        offline: false
        output_directory: null   # defaults to output_directory/IG
        namespace: null          # must match the PSR-4 mapping for the IG output directory
```

> The fabricated `generation.*` block and the `serialization.strict_validation` /
> `cache_metadata` / `debug` keys from earlier drafts do **not** exist. Use the keys above; an
> unknown key fails configuration validation.

### Environment-specific overrides

Standard Symfony per-environment config applies — for example, enabling the metadata cache warmer
in production:

```yaml
# config/packages/prod/fhir.yaml
fhir:
    serialization:
        enable_cache_warmer: true
```

## Services

The bundle registers these public service ids (each is an alias to the concrete class, so you can
also autowire by type):

| Service id | Class | Purpose |
|------------|-------|---------|
| `fhir.serialization_service` | `Component\Serialization\FHIRSerializationService` | JSON/XML serialization and deserialization |
| `fhir.validator` | `Component\Serialization\Validator\FHIRValidator` | Business-rule validation of model objects |
| `fhir.validation_service` | `Component\Validation\FHIRValidationService` | Full profile/terminology/invariant validation → `OperationOutcome` |
| `fhir.validation_message_registry` | `Component\Validation\FHIRValidationMessageRegistry` | Violation message templates |
| `fhir.model_generator` | `Component\CodeGeneration\Generator\FHIRModelGenerator` | Generates FHIR model classes |
| `fhir.package_loader` | `Component\CodeGeneration\Package\PackageLoader` | Loads FHIR packages |

### Injecting the serialization service

```php
<?php

namespace App\Service;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\PatientResource;

final class FHIRProcessingService
{
    public function __construct(
        private readonly FHIRSerializationService $serializer,
    ) {}

    public function processPatientJson(string $json): PatientResource
    {
        // deserializeFromJson() / serializeToJson() are the real method names —
        // there is no generic serialize($obj, 'json') or validate() method on this service.
        return $this->serializer->deserializeFromJson($json, PatientResource::class);
    }
}
```

See the [Serialization guide](serialization.md) for the full service API and the
[Validation component README](../../src/Component/Validation/README.md) for `FHIRValidationService`.

### Controller example

```php
<?php

namespace App\Controller\Api;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\PatientResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/fhir')]
final class FHIRApiController extends AbstractController
{
    public function __construct(
        private readonly FHIRSerializationService $serializer,
    ) {}

    #[Route('/patient', methods: ['POST'])]
    public function createPatient(Request $request): JsonResponse
    {
        try {
            $patient = $this->serializer->deserializeFromJson(
                $request->getContent(),
                PatientResource::class,
            );
        } catch (FHIRSerializationException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

        // ... persist $patient ...

        $json = $this->serializer->serializeToJson($patient);

        return new JsonResponse(
            json_decode($json, true),
            201,
            ['Content-Type' => 'application/fhir+json'],
        );
    }
}
```

## Console Commands

| Command | What it does |
|---------|--------------|
| `fhir:generate` | Generate canonical model classes from a FHIR core package |
| `fhir:generate-ig` | Generate typed Implementation Guide extension/profile classes |
| `fhir:path:evaluate` | Evaluate a FHIRPath expression against a resource |
| `fhir:path:validate` | Validate FHIRPath expression syntax |

### Generate Base FHIR Models (`fhir:generate`)

Generates canonical PHP model classes from a FHIR core package. Run once when setting up a project
or upgrading the FHIR version.

```bash
# Generate R4 models
php bin/console fhir:generate --package=hl7.fhir.r4.core -vvv

# Generate R4B models
php bin/console fhir:generate --package=hl7.fhir.r4b.core -vvv

# Offline mode (use cached packages only)
php bin/console fhir:generate --package=hl7.fhir.r4.core --offline -vvv
```

### Generate Implementation Guide Classes (`fhir:generate-ig`)

Generates typed PHP classes for a FHIR Implementation Guide. The command produces two kinds of
class, each in its own subdirectory under `IG/{version}/{slug}/`:

- **Extension classes** — typed subclasses of `Extension` with the URL baked in and value
  properties narrowed to the concrete types the IG requires.
- **Profile classes** — thin subclasses of the base resource or type carrying a `PROFILE_URL`
  constant and a `#[FHIRProfile]` attribute.

The output namespace is completely isolated from the base models so both can evolve independently.

**Recommended: configure packages in `fhir.yaml`**

```yaml
# config/packages/fhir.yaml
fhir:
    ig:
        namespace: 'App\FHIR\IG'
        output_directory: '%kernel.project_dir%/src/FHIRIG'
        offline: false
        packages:
            - hl7.fhir.us.core          # simple case
```

Then run with no arguments:

```bash
php bin/console fhir:generate-ig
```

**Override packages at runtime**

The `--package` option overrides `fhir.ig.packages` entirely when supplied. This is useful for
one-off generation or CI pipelines:

```bash
# Single IG
php bin/console fhir:generate-ig --package=hl7.fhir.us.core

# Pin a specific version
php bin/console fhir:generate-ig --package=hl7.fhir.us.core#6.1.0

# Multi-level chain (AU Base → AU Core) — order matters
php bin/console fhir:generate-ig \
    --package=hl7.fhir.au.base#1.0.0 \
    --package=hl7.fhir.au.core#1.0.0
```

**Multi-level profile inheritance**

When an IG profiles another IG (e.g. AU Core extends AU Base which extends base FHIR R4), list the
packages in dependency order. The generator builds a proper inheritance chain:

```
PatientResource          (base R4)
  └── AUBasePatientProfile   (hl7.fhir.au.base)
        └── AUCorePatientProfile   (hl7.fhir.au.core)
```

```yaml
# config/packages/fhir.yaml
fhir:
    ig:
        namespace: 'App\FHIR\IG'
        output_directory: '%kernel.project_dir%/src/FHIRIG'
        packages:
            - hl7.fhir.au.base#1.0.0   # must come before au.core
            - hl7.fhir.au.core#1.0.0
```

**PSR-4 autoloader registration**

Add the generated directory to your `composer.json` autoloader and run `composer dump-autoload`:

```json
"autoload": {
    "psr-4": {
        "App\\FHIR\\IG\\": "src/FHIRIG/"
    }
}
```

**`fhir.ig` configuration reference**

| Key | Type | Default | Description |
|-----|------|---------|-------------|
| `ig.packages` | `list<string>` | `[]` | IG packages in dependency order; accepts `name` or `name#version` |
| `ig.offline` | `bool` | `false` | Use only cached packages; skip network downloads |
| `ig.output_directory` | `string\|null` | `null` | Root directory for generated IG classes; defaults to `output_directory/IG` when null |
| `ig.namespace` | `string\|null` | `null` | Root PHP namespace for generated IG classes; must match the PSR-4 mapping |

## Testing with Bundle Services

Fetch the configured service from the test container rather than constructing it:

```php
<?php

namespace App\Tests\Service;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\PatientResource;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class FHIRSerializationServiceTest extends KernelTestCase
{
    public function testRoundTrip(): void
    {
        self::bootKernel();
        /** @var FHIRSerializationService $serializer */
        $serializer = static::getContainer()->get('fhir.serialization_service');

        $patient  = new PatientResource(id: 'test-123', active: true);
        $json     = $serializer->serializeToJson($patient);
        $restored = $serializer->deserializeFromJson($json, PatientResource::class);

        self::assertSame('test-123', $restored->id);
    }
}
```

## Troubleshooting

| Symptom | Check |
|---------|-------|
| `Bundle "FHIRBundle" not found` | Bundle registered in `config/bundles.php` with the correct FQCN (`Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle`). |
| `Service "fhir.serialization_service" not found` | `php bin/console cache:clear`, then `php bin/console debug:container fhir`. |
| `Invalid configuration for path "fhir..."` | An unsupported key — compare against the configuration tree above. |

```bash
# Inspect what the bundle registered
php bin/console debug:container fhir

# Dump the effective configuration
php bin/console debug:config fhir
```
