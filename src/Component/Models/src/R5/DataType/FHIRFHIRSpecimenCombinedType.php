<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRSpecimenCombined;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRSpecimenCombined
 *
 * @description Code type wrapper for FHIRSpecimenCombined enum
 */
class FHIRFHIRSpecimenCombinedType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSpecimenCombined|string|null $value The code value */
        public FHIRFHIRSpecimenCombined|string|null $value = null,
    ) {
    }
}
