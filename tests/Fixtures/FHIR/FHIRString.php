<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Fixtures\FHIR;

use Ardenexal\FHIRTools\Attributes\FHIRPrimitive;

/**
 * Test FHIR String primitive for testing serialization
 *
 * @author Kiro AI Assistant
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
