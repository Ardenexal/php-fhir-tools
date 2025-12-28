<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRLinkRelationTypes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRLinkRelationTypes
 *
 * @description Code type wrapper for FHIRLinkRelationTypes enum
 */
class FHIRFHIRLinkRelationTypesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRLinkRelationTypes|string|null $value The code value */
        public FHIRFHIRLinkRelationTypes|string|null $value = null,
    ) {
    }
}
