<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\SubscriptionSearchModifier;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type SubscriptionSearchModifier
 *
 * @description Code type wrapper for SubscriptionSearchModifier enum
 */
class SubscriptionSearchModifierType extends CodePrimitive
{
    public function __construct(
        /** @param SubscriptionSearchModifier|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
