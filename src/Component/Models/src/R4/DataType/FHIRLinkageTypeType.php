<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRLinkageType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRLinkageType
 *
 * @description Code type wrapper for FHIRLinkageType enum
 */
class FHIRLinkageTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRLinkageType|string|null $value The code value */
        public FHIRLinkageType|string|null $value = null,
    ) {
    }
}
