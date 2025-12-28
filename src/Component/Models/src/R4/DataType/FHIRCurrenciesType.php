<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCurrencies;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCurrencies
 *
 * @description Code type wrapper for FHIRCurrencies enum
 */
class FHIRCurrenciesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRCurrencies|string|null $value The code value */
        public FHIRCurrencies|string|null $value = null,
    ) {
    }
}
