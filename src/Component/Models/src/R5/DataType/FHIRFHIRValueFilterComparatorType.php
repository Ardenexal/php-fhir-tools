<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRValueFilterComparator;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRValueFilterComparator
 *
 * @description Code type wrapper for FHIRValueFilterComparator enum
 */
class FHIRFHIRValueFilterComparatorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRValueFilterComparator|string|null $value The code value */
        public FHIRFHIRValueFilterComparator|string|null $value = null,
    ) {
    }
}
