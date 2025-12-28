<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDiscriminatorType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDiscriminatorType
 *
 * @description Code type wrapper for FHIRDiscriminatorType enum
 */
class FHIRDiscriminatorTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDiscriminatorType|string|null $value The code value */
        public FHIRDiscriminatorType|string|null $value = null,
    ) {
    }
}
