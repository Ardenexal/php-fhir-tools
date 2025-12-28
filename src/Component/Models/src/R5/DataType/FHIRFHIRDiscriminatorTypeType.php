<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDiscriminatorType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDiscriminatorType
 *
 * @description Code type wrapper for FHIRDiscriminatorType enum
 */
class FHIRFHIRDiscriminatorTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDiscriminatorType|string|null $value The code value */
        public FHIRFHIRDiscriminatorType|string|null $value = null,
    ) {
    }
}
