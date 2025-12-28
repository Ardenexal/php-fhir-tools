<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRAllLanguages;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAllLanguages
 *
 * @description Code type wrapper for FHIRAllLanguages enum
 */
class FHIRAllLanguagesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAllLanguages|string|null $value The code value */
        public FHIRAllLanguages|string|null $value = null,
    ) {
    }
}
