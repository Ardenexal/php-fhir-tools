<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRSearchProcessingModeType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRSearchProcessingModeType
 *
 * @description Code type wrapper for FHIRSearchProcessingModeType enum
 */
class FHIRFHIRSearchProcessingModeTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSearchProcessingModeType|string|null $value The code value */
        public FHIRFHIRSearchProcessingModeType|string|null $value = null,
    ) {
    }
}
