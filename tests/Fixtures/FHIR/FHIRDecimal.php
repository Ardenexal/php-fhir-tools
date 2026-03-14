<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Fixtures\FHIR;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;

/**
 * Test FHIR Decimal primitive for testing serialization
 *
 * @author Ardenexal
 */
#[FHIRPrimitive(
    primitiveType: 'decimal',
    fhirVersion: 'R4B',
)]
class FHIRDecimal
{
    public function __construct(
        public ?string $value = null,
        public ?array $extension = null
    ) {
    }
}
