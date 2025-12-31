<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\DataType\FHIRElement;

/**
 * Mock FHIRString primitive for testing.
 */
#[FHIRPrimitive(primitiveType: 'string', fhirVersion: 'R4B')]
class FHIRString extends FHIRElement
{
    public function __construct(
        ?string $id = null,
        array $extension = [],
        public ?string $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}
