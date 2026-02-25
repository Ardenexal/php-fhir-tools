<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\SubscriptionStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type SubscriptionStatusCodes
 *
 * @description Code type wrapper for SubscriptionStatusCodes enum
 */
class SubscriptionStatusCodesType extends CodePrimitive
{
    public function __construct(
        /** @param SubscriptionStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
