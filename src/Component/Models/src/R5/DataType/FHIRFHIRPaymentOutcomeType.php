<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRPaymentOutcome;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRPaymentOutcome
 *
 * @description Code type wrapper for FHIRPaymentOutcome enum
 */
class FHIRFHIRPaymentOutcomeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRPaymentOutcome|string|null $value The code value */
        public FHIRFHIRPaymentOutcome|string|null $value = null,
    ) {
    }
}
