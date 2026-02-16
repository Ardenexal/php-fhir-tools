<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Fixtures\FHIR;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;

/**
 * Test FHIR String primitive for testing serialization
 *
 * @author Ardenexal
 */
#[FHIRPrimitive(
    primitiveType: 'string',
    fhirVersion: 'R4B',
)]
class FHIRString
{
    public function __construct(
        public ?string $value = null,
        public ?array $extension = null
    ) {
    }
}
