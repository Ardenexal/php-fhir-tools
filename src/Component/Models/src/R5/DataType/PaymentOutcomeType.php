<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\PaymentOutcome;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type PaymentOutcome
 *
 * @description Code type wrapper for PaymentOutcome enum
 */
class PaymentOutcomeType extends CodePrimitive
{
    public function __construct(
        /** @param PaymentOutcome|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
