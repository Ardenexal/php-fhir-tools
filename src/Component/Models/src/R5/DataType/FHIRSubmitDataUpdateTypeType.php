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
        /** @param FHIRSubmitDataUpdateType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
