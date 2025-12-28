<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRPaymentOutcome;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRPaymentOutcome
 *
 * @description Code type wrapper for FHIRPaymentOutcome enum
 */
class FHIRPaymentOutcomeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRPaymentOutcome|string|null $value The code value */
        public FHIRPaymentOutcome|string|null $value = null,
    ) {
    }
}
