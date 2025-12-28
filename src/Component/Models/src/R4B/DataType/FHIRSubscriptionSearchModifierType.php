<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRSubscriptionSearchModifier;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSubscriptionSearchModifier
 *
 * @description Code type wrapper for FHIRSubscriptionSearchModifier enum
 */
class FHIRSubscriptionSearchModifierType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSubscriptionSearchModifier|string|null $value The code value */
        public FHIRSubscriptionSearchModifier|string|null $value = null,
    ) {
    }
}
