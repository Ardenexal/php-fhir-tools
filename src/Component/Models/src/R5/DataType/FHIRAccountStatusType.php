<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAccountStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAccountStatus
 *
 * @description Code type wrapper for FHIRAccountStatus enum
 */
class FHIRAccountStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRAccountStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
