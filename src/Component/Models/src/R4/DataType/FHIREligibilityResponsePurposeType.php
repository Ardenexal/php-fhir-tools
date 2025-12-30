<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREligibilityResponsePurpose;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREligibilityResponsePurpose
 *
 * @description Code type wrapper for FHIREligibilityResponsePurpose enum
 */
class FHIREligibilityResponsePurposeType extends FHIRCode
{
    public function __construct(
        /** @param FHIREligibilityResponsePurpose|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
