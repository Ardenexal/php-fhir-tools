<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

final class FHIRModifierExtensionValidationTest extends TestCase
{
    private FHIRValidationService $service;

    private FHIRIGTypeRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = new FHIRIGTypeRegistry(
            extensionMappings: ['http://example.org/known-modifier' => ['R4' => self::class]],
        );

        $this->service = new FHIRValidationService(
            Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator(),
            new FHIRPathService(),
            $this->registry,
        );
    }

    private function makeExtension(?string $url): object
    {
        return new class ($url) {
            public function __construct(private readonly ?string $url)
            {
            }

            public function getExtensionUrl(): ?string
            {
                return $this->url;
            }
        };
    }

    private function makeResource(array $modifierExtensions = [], array $nestedObjects = []): object
    {
        return new class ($modifierExtensions, $nestedObjects) {
            public array $modifierExtension;

            public array $nested;

            public function __construct(array $modifierExtensions, array $nestedObjects)
            {
                $this->modifierExtension = $modifierExtensions;
                $this->nested            = $nestedObjects;
            }
        };
    }

    public function testNoModifierExtensionsProducesNoViolation(): void
    {
        $resource = $this->makeResource();
        $report   = $this->service->validate($resource);

        self::assertFalse($report->hasErrors(), 'No modifier extensions → no error');
    }

    public function testKnownModifierExtensionUrlProducesNoViolation(): void
    {
        $resource = $this->makeResource([
            $this->makeExtension('http://example.org/known-modifier'),
        ]);
        $report = $this->service->validate($resource);

        self::assertFalse($report->hasErrors(), 'Known extension URL must produce no error');
    }

    public function testUnknownModifierExtensionUrlProducesError(): void
    {
        $resource = $this->makeResource([
            $this->makeExtension('http://example.org/unknown-modifier'),
        ]);
        $report = $this->service->validate($resource);

        self::assertTrue($report->hasErrors(), 'Unknown modifier extension must produce fhir:error');

        $errors = $report->errors();
        self::assertCount(1, $errors);
        self::assertStringContainsString('http://example.org/unknown-modifier', $errors[0]->message);
        self::assertStringContainsString('Unknown modifier extension', $errors[0]->message);
    }

    public function testNestedObjectWithUnknownModifierExtensionProducesError(): void
    {
        $nested = new class () {
            public array $modifierExtension = [];
        };
        $nested->modifierExtension = [
            new class () {
                public function getExtensionUrl(): string
                {
                    return 'http://example.org/nested-unknown-modifier';
                }
            },
        ];

        $resource = $this->makeResource([], [$nested]);
        $report   = $this->service->validate($resource);

        self::assertTrue($report->hasErrors(), 'Nested unknown modifier extension must produce fhir:error');
        $error = $report->errors()[0];
        self::assertStringContainsString('http://example.org/nested-unknown-modifier', $error->message);
        self::assertStringContainsString('nested', $error->path, 'Violation path must reference the nested property');
    }

    public function testUnknownRegularExtensionProducesNoViolation(): void
    {
        $resource = new class () {
            public array $modifierExtension = [];

            public array $extension;
        };
        $resource->extension = [
            new class () {
                public function getExtensionUrl(): string
                {
                    return 'http://example.org/unknown-regular-extension';
                }
            },
        ];
        $report = $this->service->validate($resource);

        self::assertFalse(
            $report->hasErrors(),
            'Regular (non-modifier) extensions with unknown URLs must produce no violation per FHIR spec',
        );
    }

    public function testResourceWithoutModifierExtensionPropertyProducesNoViolation(): void
    {
        $resource = new class () {
            public string $value = 'test';
        };
        $report = $this->service->validate($resource);

        self::assertFalse($report->hasErrors(), 'Resource with no modifierExtension property must produce no error');
    }

    public function testNullRegistrySkipsModifierExtensionCheck(): void
    {
        $serviceWithoutRegistry = new FHIRValidationService(
            Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator(),
            new FHIRPathService(),
        );

        $resource = $this->makeResource([
            $this->makeExtension('http://example.org/unknown-modifier'),
        ]);
        $report = $serviceWithoutRegistry->validate($resource);

        self::assertFalse($report->hasErrors(), 'Without registry, modifier extension check must be skipped');
    }
}
