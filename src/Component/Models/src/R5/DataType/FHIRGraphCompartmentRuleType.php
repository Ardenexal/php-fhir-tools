<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRGraphCompartmentRule;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRGraphCompartmentRule
 *
 * @description Code type wrapper for FHIRGraphCompartmentRule enum
 */
class FHIRGraphCompartmentRuleType extends FHIRCode
{
    public function __construct(
        /** @param FHIRGraphCompartmentRule|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
