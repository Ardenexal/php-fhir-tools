<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAccountStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAccountStatus
 *
 * @description Code type wrapper for FHIRAccountStatus enum
 */
class FHIRAccountStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAccountStatus|string|null $value The code value */
        public FHIRAccountStatus|string|null $value = null,
    ) {
    }
}
