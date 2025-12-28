<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRListStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRListStatus
 *
 * @description Code type wrapper for FHIRListStatus enum
 */
class FHIRListStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRListStatus|string|null $value The code value */
        public FHIRListStatus|string|null $value = null,
    ) {
    }
}
