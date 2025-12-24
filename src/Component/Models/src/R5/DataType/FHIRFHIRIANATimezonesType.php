<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRIANATimezones;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRIANATimezones
 *
 * @description Code type wrapper for FHIRIANATimezones enum
 */
class FHIRFHIRIANATimezonesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRIANATimezones|string|null $value The code value */
        public FHIRFHIRIANATimezones|string|null $value = null,
    ) {
    }
}
