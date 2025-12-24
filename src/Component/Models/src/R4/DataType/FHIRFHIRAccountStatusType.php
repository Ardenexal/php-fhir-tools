<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAccountStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAccountStatus
 *
 * @description Code type wrapper for FHIRAccountStatus enum
 */
class FHIRFHIRAccountStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAccountStatus|string|null $value The code value */
        public FHIRFHIRAccountStatus|string|null $value = null,
    ) {
    }
}
