<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRConceptMapEquivalence;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConceptMapEquivalence
 *
 * @description Code type wrapper for FHIRConceptMapEquivalence enum
 */
class FHIRConceptMapEquivalenceType extends FHIRCode
{
    public function __construct(
        /** @var FHIRConceptMapEquivalence|string|null $value The code value */
        public FHIRConceptMapEquivalence|string|null $value = null,
    ) {
    }
}
