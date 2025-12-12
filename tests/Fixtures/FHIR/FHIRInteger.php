<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Fixtures\FHIR;

use Ardenexal\FHIRTools\Attributes\FHIRPrimitive;

/**
 * Test FHIR Integer primitive for testing serialization
 *
 * @author Kiro AI Assistant
 */
#[FHIRPrimitive(
    primitiveType: 'integer',
    fhirVersion: 'R4B',
)]
class FHIRInteger
{
    public function __construct(
        public ?int $value = null,
        public ?array $extension = null
    ) {
    }
}