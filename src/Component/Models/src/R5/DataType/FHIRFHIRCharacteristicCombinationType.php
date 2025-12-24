<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRCharacteristicCombination;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRCharacteristicCombination
 *
 * @description Code type wrapper for FHIRCharacteristicCombination enum
 */
class FHIRFHIRCharacteristicCombinationType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCharacteristicCombination|string|null $value The code value */
        public FHIRFHIRCharacteristicCombination|string|null $value = null,
    ) {
    }
}
