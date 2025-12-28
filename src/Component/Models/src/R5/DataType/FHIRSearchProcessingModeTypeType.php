<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRSearchProcessingModeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSearchProcessingModeType
 *
 * @description Code type wrapper for FHIRSearchProcessingModeType enum
 */
class FHIRSearchProcessingModeTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSearchProcessingModeType|string|null $value The code value */
        public FHIRSearchProcessingModeType|string|null $value = null,
    ) {
    }
}
