<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIREnrollmentOutcome;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIREnrollmentOutcome
 *
 * @description Code type wrapper for FHIREnrollmentOutcome enum
 */
class FHIRFHIREnrollmentOutcomeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIREnrollmentOutcome|string|null $value The code value */
        public FHIRFHIREnrollmentOutcome|string|null $value = null,
    ) {
    }
}
