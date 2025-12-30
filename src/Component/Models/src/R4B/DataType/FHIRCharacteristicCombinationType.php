<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRCharacteristicCombination;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCharacteristicCombination
 *
 * @description Code type wrapper for FHIRCharacteristicCombination enum
 */
class FHIRCharacteristicCombinationType extends FHIRCode
{
    public function __construct(
        /** @param FHIRCharacteristicCombination|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
