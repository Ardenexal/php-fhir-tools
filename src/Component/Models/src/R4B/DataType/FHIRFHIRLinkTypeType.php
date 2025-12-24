<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRLinkType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRLinkType
 *
 * @description Code type wrapper for FHIRLinkType enum
 */
class FHIRFHIRLinkTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRLinkType|string|null $value The code value */
        public FHIRFHIRLinkType|string|null $value = null,
    ) {
    }
}
