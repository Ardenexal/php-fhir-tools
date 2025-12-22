<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Fixtures\FHIR;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;

/**
 * Test FHIR Boolean primitive for testing serialization
 *
 * @author Kiro AI Assistant
 */
#[FHIRPrimitive(
    primitiveType: 'boolean',
    fhirVersion: 'R4B',
)]
class FHIRBoolean
{
    public function __construct(
        public ?bool $value = null,
        public ?array $extension = null
    ) {
    }
}
