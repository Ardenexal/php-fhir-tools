<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIREnrollmentOutcome;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREnrollmentOutcome
 *
 * @description Code type wrapper for FHIREnrollmentOutcome enum
 */
class FHIREnrollmentOutcomeType extends FHIRCode
{
    public function __construct(
        /** @var FHIREnrollmentOutcome|string|null $value The code value */
        public FHIREnrollmentOutcome|string|null $value = null,
    ) {
    }
}
