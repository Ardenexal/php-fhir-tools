<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRPropertyRepresentation;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRPropertyRepresentation
 *
 * @description Code type wrapper for FHIRPropertyRepresentation enum
 */
class FHIRFHIRPropertyRepresentationType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRPropertyRepresentation|string|null $value The code value */
        public FHIRFHIRPropertyRepresentation|string|null $value = null,
    ) {
    }
}
