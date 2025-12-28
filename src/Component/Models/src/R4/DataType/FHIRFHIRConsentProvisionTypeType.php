<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConsentProvisionType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConsentProvisionType
 *
 * @description Code type wrapper for FHIRConsentProvisionType enum
 */
class FHIRFHIRConsentProvisionTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRConsentProvisionType|string|null $value The code value */
        public FHIRFHIRConsentProvisionType|string|null $value = null,
    ) {
    }
}
