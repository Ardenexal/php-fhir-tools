<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Fixtures\FHIR;

use Ardenexal\FHIRTools\Attributes\FHIRComplexType;

/**
 * Test FHIR Reference complex type for testing polymorphic references
 *
 * @author Kiro AI Assistant
 */
#[FHIRComplexType(
    typeName: 'Reference',
    fhirVersion: 'R4B',
)]
class FHIRReference
{
    public function __construct(
        public ?string $reference = null,
        public ?string $type = null,
        public ?string $identifier = null,
        public ?string $display = null,
        /** @var array<mixed>|null */
        public ?array $extension = null
    ) {
    }
}
