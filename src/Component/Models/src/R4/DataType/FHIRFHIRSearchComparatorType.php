<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSearchComparator;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSearchComparator
 *
 * @description Code type wrapper for FHIRSearchComparator enum
 */
class FHIRFHIRSearchComparatorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSearchComparator|string|null $value The code value */
        public FHIRFHIRSearchComparator|string|null $value = null,
    ) {
    }
}
