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
        /** @param FHIRSearchProcessingModeType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
