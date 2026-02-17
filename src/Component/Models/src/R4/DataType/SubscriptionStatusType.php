<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\SubscriptionStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type SubscriptionStatus
 *
 * @description Code type wrapper for SubscriptionStatus enum
 */
class SubscriptionStatusType extends CodePrimitive
{
    public function __construct(
        /** @param SubscriptionStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
