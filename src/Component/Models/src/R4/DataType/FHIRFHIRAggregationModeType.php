<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAggregationMode;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAggregationMode
 *
 * @description Code type wrapper for FHIRAggregationMode enum
 */
class FHIRFHIRAggregationModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAggregationMode|string|null $value The code value */
        public FHIRFHIRAggregationMode|string|null $value = null,
    ) {
    }
}
