<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConsentDataMeaning;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConsentDataMeaning
 *
 * @description Code type wrapper for FHIRConsentDataMeaning enum
 */
class FHIRConsentDataMeaningType extends FHIRCode
{
    public function __construct(
        /** @var FHIRConsentDataMeaning|string|null $value The code value */
        public FHIRConsentDataMeaning|string|null $value = null,
    ) {
    }
}
