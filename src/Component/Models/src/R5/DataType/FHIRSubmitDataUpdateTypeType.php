<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRSubmitDataUpdateType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSubmitDataUpdateType
 *
 * @description Code type wrapper for FHIRSubmitDataUpdateType enum
 */
class FHIRSubmitDataUpdateTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSubmitDataUpdateType|string|null $value The code value */
        public FHIRSubmitDataUpdateType|string|null $value = null,
    ) {
    }
}
