<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAdverseEventOutcome;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAdverseEventOutcome
 *
 * @description Code type wrapper for FHIRAdverseEventOutcome enum
 */
class FHIRFHIRAdverseEventOutcomeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAdverseEventOutcome|string|null $value The code value */
        public FHIRFHIRAdverseEventOutcome|string|null $value = null,
    ) {
    }
}
