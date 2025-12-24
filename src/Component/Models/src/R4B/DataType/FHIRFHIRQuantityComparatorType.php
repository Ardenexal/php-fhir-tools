<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRQuantityComparator;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRQuantityComparator
 *
 * @description Code type wrapper for FHIRQuantityComparator enum
 */
class FHIRFHIRQuantityComparatorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRQuantityComparator|string|null $value The code value */
        public FHIRFHIRQuantityComparator|string|null $value = null,
    ) {
    }
}
