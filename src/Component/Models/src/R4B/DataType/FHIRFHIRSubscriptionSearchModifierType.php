<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRSubscriptionSearchModifier;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRSubscriptionSearchModifier
 *
 * @description Code type wrapper for FHIRSubscriptionSearchModifier enum
 */
class FHIRFHIRSubscriptionSearchModifierType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSubscriptionSearchModifier|string|null $value The code value */
        public FHIRFHIRSubscriptionSearchModifier|string|null $value = null,
    ) {
    }
}
