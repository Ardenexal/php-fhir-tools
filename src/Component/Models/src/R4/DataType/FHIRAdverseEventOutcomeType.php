<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdverseEventOutcome;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAdverseEventOutcome
 *
 * @description Code type wrapper for FHIRAdverseEventOutcome enum
 */
class FHIRAdverseEventOutcomeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAdverseEventOutcome|string|null $value The code value */
        public FHIRAdverseEventOutcome|string|null $value = null,
    ) {
    }
}
