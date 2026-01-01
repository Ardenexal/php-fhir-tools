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
        /** @param FHIRValueFilterComparator|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
