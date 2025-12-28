<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSpecimenContainedPreference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSpecimenContainedPreference
 *
 * @description Code type wrapper for FHIRSpecimenContainedPreference enum
 */
class FHIRFHIRSpecimenContainedPreferenceType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSpecimenContainedPreference|string|null $value The code value */
        public FHIRFHIRSpecimenContainedPreference|string|null $value = null,
    ) {
    }
}
