<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRQuantityComparator;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRQuantityComparator
 *
 * @description Code type wrapper for FHIRQuantityComparator enum
 */
class FHIRQuantityComparatorType extends FHIRCode
{
    public function __construct(
        /** @param FHIRQuantityComparator|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
