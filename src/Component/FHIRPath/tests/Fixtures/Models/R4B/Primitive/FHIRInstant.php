<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\DataType\FHIRElement;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRInstant as FHIRInstantValue;

/**
 * Mock FHIRInstant primitive for testing.
 */
#[FHIRPrimitive(primitiveType: 'instant', fhirVersion: 'R4B')]
class FHIRInstant extends FHIRElement
{
    public function __construct(
        ?string $id = null,
        array $extension = [],
        public ?FHIRInstantValue $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}
