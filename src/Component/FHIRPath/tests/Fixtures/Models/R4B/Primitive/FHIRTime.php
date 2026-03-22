<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\DataType\FHIRElement;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRTime as FHIRTimeValue;

/**
 * Mock FHIRTime primitive for testing.
 */
#[FHIRPrimitive(primitiveType: 'time', fhirVersion: 'R4B')]
class FHIRTime extends FHIRElement
{
    public function __construct(
        ?string $id = null,
        array $extension = [],
        public ?FHIRTimeValue $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}
