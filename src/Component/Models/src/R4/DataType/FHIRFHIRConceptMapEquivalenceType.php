<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConceptMapEquivalence;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRConceptMapEquivalence
 *
 * @description Code type wrapper for FHIRConceptMapEquivalence enum
 */
class FHIRFHIRConceptMapEquivalenceType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRConceptMapEquivalence|string|null $value The code value */
        public FHIRFHIRConceptMapEquivalence|string|null $value = null,
    ) {
    }
}
