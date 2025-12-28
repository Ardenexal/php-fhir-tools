<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAllLanguages;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAllLanguages
 *
 * @description Code type wrapper for FHIRAllLanguages enum
 */
class FHIRFHIRAllLanguagesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAllLanguages|string|null $value The code value */
        public FHIRFHIRAllLanguages|string|null $value = null,
    ) {
    }
}
