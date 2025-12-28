<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSpecimenContainedPreference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSpecimenContainedPreference
 *
 * @description Code type wrapper for FHIRSpecimenContainedPreference enum
 */
class FHIRSpecimenContainedPreferenceType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSpecimenContainedPreference|string|null $value The code value */
        public FHIRSpecimenContainedPreference|string|null $value = null,
    ) {
    }
}
