<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

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
        /** @param FHIRDiscriminatorType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
