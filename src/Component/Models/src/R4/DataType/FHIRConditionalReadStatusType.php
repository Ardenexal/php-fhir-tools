<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConditionalReadStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConditionalReadStatus
 *
 * @description Code type wrapper for FHIRConditionalReadStatus enum
 */
class FHIRConditionalReadStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRConditionalReadStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
