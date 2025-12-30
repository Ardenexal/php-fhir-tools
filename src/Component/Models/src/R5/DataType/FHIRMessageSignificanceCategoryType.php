<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMessageSignificanceCategory;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMessageSignificanceCategory
 *
 * @description Code type wrapper for FHIRMessageSignificanceCategory enum
 */
class FHIRMessageSignificanceCategoryType extends FHIRCode
{
    public function __construct(
        /** @param FHIRMessageSignificanceCategory|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
