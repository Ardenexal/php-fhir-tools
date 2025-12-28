<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRResourceType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRResourceType
 *
 * @description Code type wrapper for FHIRResourceType enum
 */
class FHIRResourceTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRResourceType|string|null $value The code value */
        public FHIRResourceType|string|null $value = null,
    ) {
    }
}
