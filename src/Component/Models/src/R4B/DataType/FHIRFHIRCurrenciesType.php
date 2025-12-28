<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRCurrencies;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCurrencies
 *
 * @description Code type wrapper for FHIRCurrencies enum
 */
class FHIRFHIRCurrenciesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCurrencies|string|null $value The code value */
        public FHIRFHIRCurrencies|string|null $value = null,
    ) {
    }
}
