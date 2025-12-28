<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRValueFilterComparator;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRValueFilterComparator
 *
 * @description Code type wrapper for FHIRValueFilterComparator enum
 */
class FHIRValueFilterComparatorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRValueFilterComparator|string|null $value The code value */
        public FHIRValueFilterComparator|string|null $value = null,
    ) {
    }
}
