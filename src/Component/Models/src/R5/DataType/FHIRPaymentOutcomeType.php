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
        /** @param FHIRPaymentOutcome|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
