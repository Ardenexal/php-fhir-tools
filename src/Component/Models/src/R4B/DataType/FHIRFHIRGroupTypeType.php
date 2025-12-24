<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRGroupType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRGroupType
 *
 * @description Code type wrapper for FHIRGroupType enum
 */
class FHIRFHIRGroupTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRGroupType|string|null $value The code value */
        public FHIRFHIRGroupType|string|null $value = null,
    ) {
    }
}
