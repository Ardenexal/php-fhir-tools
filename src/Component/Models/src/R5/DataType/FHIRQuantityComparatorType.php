<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

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
        /** @var FHIRQuantityComparator|string|null $value The code value */
        public FHIRQuantityComparator|string|null $value = null,
    ) {
    }
}
