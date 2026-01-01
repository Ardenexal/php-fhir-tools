<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRVisionEyes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRVisionEyes
 *
 * @description Code type wrapper for FHIRVisionEyes enum
 */
class FHIRVisionEyesType extends FHIRCode
{
    public function __construct(
        /** @param FHIRVisionEyes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
