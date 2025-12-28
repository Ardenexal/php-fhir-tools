<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIREligibilityOutcome;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREligibilityOutcome
 *
 * @description Code type wrapper for FHIREligibilityOutcome enum
 */
class FHIRFHIREligibilityOutcomeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIREligibilityOutcome|string|null $value The code value */
        public FHIRFHIREligibilityOutcome|string|null $value = null,
    ) {
    }
}
