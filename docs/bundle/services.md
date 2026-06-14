---
description: Inject and use FHIR Tools services in a Symfony application.
icon: diagram-project
---

# Inject and use FHIR services

When the bundle is enabled, it registers the serialization, validation, code-generation, and
FHIRPath services in the Symfony container. Each public service is available both by a `fhir.*`
alias and — because the alias points at the concrete class — by autowiring the class type directly.

## Registered services

The aliases below are declared in the bundle's `Resources/config/services.yaml` and are marked
`public: true`, so you can fetch them from the container or autowire the underlying class.

| Service id | Class | Purpose |
| --- | --- | --- |
| `fhir.serialization_service` | `Component\Serialization\FHIRSerializationService` | JSON/XML serialization and deserialization |
| `fhir.validation_service` | `Component\Validation\FHIRValidationService` | Profile/terminology/invariant validation → `OperationOutcome` |
| `fhir.validation_message_registry` | `Component\Validation\FHIRValidationMessageRegistry` | Violation message templates |
| `fhir.validator` | `Component\Serialization\Validator\FHIRValidator` | Business-rule validation of model objects |
| `fhir.model_generator` | `Component\CodeGeneration\Generator\FHIRModelGenerator` | Generates FHIR model classes |
| `fhir.package_loader` | `Component\CodeGeneration\Package\PackageLoader` | Loads FHIR packages from registries |

The FHIRPath service (`Component\FHIRPath\Service\FHIRPathService`) is also registered as public, but
without a `fhir.*` alias — autowire it by its class type.

{% hint style="info" %}
All classes live under the `Ardenexal\FHIRTools\` namespace — the table abbreviates
`Ardenexal\FHIRTools\Component\…` to `Component\…`. For example,
`fhir.serialization_service` resolves to
`Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService`.
{% endhint %}

The `FHIRSerializationService` is registered per FHIR version by the bundle's
`FHIRVersionedSerializerPass` at compile time; the `fhir.serialization_service` alias resolves to the
default-version instance.

## Using services

Autowire any of the registered classes by type. The serialization service exposes
`serializeToJson()` / `deserializeFromJson()` — there is no generic `serialize($obj, 'json')` or
`validate()` method on it.

```php
<?php

namespace App\Service;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\PatientResource;

final class FHIRProcessingService
{
    public function __construct(
        private readonly FHIRSerializationService $serializer,
        private readonly FHIRPathService $pathService,
    ) {}

    public function processPatientJson(string $json): PatientResource
    {
        return $this->serializer->deserializeFromJson($json, PatientResource::class);
    }

    public function givenNames(PatientResource $patient): array
    {
        return $this->pathService->evaluate('name.given', $patient)->toArray();
    }
}
```

## Controller example

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

## Testing with bundle services

Fetch the configured service from the test container rather than constructing it by hand. Public
aliases like `fhir.serialization_service` are reachable via `static::getContainer()->get(...)`.

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

{% hint style="info" %}
To wire a real terminology server, override the `FHIRTerminologyClientInterface` alias in your
application's `services.yaml`. By default it points at `NullFHIRTerminologyClient`.
{% endhint %}

The `fhir` configuration tree (validation, caching, IG, package settings) is documented separately —
see [Configuration](configuration.md). Inspect what was registered at runtime with
`php bin/console debug:container fhir` and dump the effective config with
`php bin/console debug:config fhir`.
