<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRLinkageType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRLinkageType
 *
 * @description Code type wrapper for FHIRLinkageType enum
 */
class FHIRFHIRLinkageTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRLinkageType|string|null $value The code value */
        public FHIRFHIRLinkageType|string|null $value = null,
    ) {
    }
}
