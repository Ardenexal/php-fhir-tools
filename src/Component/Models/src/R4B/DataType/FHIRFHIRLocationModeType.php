<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRLocationMode;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRLocationMode
 *
 * @description Code type wrapper for FHIRLocationMode enum
 */
class FHIRFHIRLocationModeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRLocationMode|string|null $value The code value */
        public FHIRFHIRLocationMode|string|null $value = null,
    ) {
    }
}
