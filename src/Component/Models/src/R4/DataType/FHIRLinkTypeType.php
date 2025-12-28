<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRLinkType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRLinkType
 *
 * @description Code type wrapper for FHIRLinkType enum
 */
class FHIRLinkTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRLinkType|string|null $value The code value */
        public FHIRLinkType|string|null $value = null,
    ) {
    }
}
