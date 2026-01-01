<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREligibilityRequestPurpose;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREligibilityRequestPurpose
 *
 * @description Code type wrapper for FHIREligibilityRequestPurpose enum
 */
class FHIREligibilityRequestPurposeType extends FHIRCode
{
    public function __construct(
        /** @param FHIREligibilityRequestPurpose|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
