<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRPropertyRepresentation;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRPropertyRepresentation
 *
 * @description Code type wrapper for FHIRPropertyRepresentation enum
 */
class FHIRPropertyRepresentationType extends FHIRCode
{
    public function __construct(
        /** @var FHIRPropertyRepresentation|string|null $value The code value */
        public FHIRPropertyRepresentation|string|null $value = null,
    ) {
    }
}
