<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRIANATimezones;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRIANATimezones
 *
 * @description Code type wrapper for FHIRIANATimezones enum
 */
class FHIRIANATimezonesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRIANATimezones|string|null $value The code value */
        public FHIRIANATimezones|string|null $value = null,
    ) {
    }
}
