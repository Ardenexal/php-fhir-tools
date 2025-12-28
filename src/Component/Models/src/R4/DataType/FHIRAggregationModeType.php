<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAggregationMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAggregationMode
 *
 * @description Code type wrapper for FHIRAggregationMode enum
 */
class FHIRAggregationModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAggregationMode|string|null $value The code value */
        public FHIRAggregationMode|string|null $value = null,
    ) {
    }
}
