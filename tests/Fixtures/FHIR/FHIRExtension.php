<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Fixtures\FHIR;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * Test FHIR Extension complex type with choice elements (value[x] pattern)
 *
 * @author Kiro AI Assistant
 */
#[FHIRComplexType(
    typeName: 'Extension',
    fhirVersion: 'R4B',
)]
class FHIRExtension
{
    public function __construct(
        public ?string $url = null,
        public ?string $valueString = null,
        public ?int $valueInteger = null,
        public ?bool $valueBoolean = null,
        /** @var array<mixed>|null */
        public ?array $valueCodeableConcept = null,
        /** @var array<mixed>|null */
        public ?array $extension = null
    ) {
    }
}
