<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSearchComparator;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSearchComparator
 *
 * @description Code type wrapper for FHIRSearchComparator enum
 */
class FHIRSearchComparatorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSearchComparator|string|null $value The code value */
        public FHIRSearchComparator|string|null $value = null,
    ) {
    }
}
