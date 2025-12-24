<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConsentDataMeaning;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRConsentDataMeaning
 *
 * @description Code type wrapper for FHIRConsentDataMeaning enum
 */
class FHIRFHIRConsentDataMeaningType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRConsentDataMeaning|string|null $value The code value */
        public FHIRFHIRConsentDataMeaning|string|null $value = null,
    ) {
    }
}
