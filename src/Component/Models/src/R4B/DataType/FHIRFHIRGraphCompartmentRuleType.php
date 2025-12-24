<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRGraphCompartmentRule;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRGraphCompartmentRule
 *
 * @description Code type wrapper for FHIRGraphCompartmentRule enum
 */
class FHIRFHIRGraphCompartmentRuleType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRGraphCompartmentRule|string|null $value The code value */
        public FHIRFHIRGraphCompartmentRule|string|null $value = null,
    ) {
    }
}
