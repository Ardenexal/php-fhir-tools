<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Fixtures\FHIR;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;

/**
 * Test FHIR Decimal primitive for testing serialization
 *
 * @author Kiro AI Assistant
 */
#[FHIRPrimitive(
    primitiveType: 'decimal',
    fhirVersion: 'R4B',
)]
class FHIRDecimal
{
    public function __construct(
        public ?float $value = null,
        public ?array $extension = null
    ) {
    }
}
