<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRGroupType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRGroupType
 *
 * @description Code type wrapper for FHIRGroupType enum
 */
class FHIRGroupTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRGroupType|string|null $value The code value */
        public FHIRGroupType|string|null $value = null,
    ) {
    }
}
