<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRCommonLanguages;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCommonLanguages
 *
 * @description Code type wrapper for FHIRCommonLanguages enum
 */
class FHIRCommonLanguagesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRCommonLanguages|string|null $value The code value */
        public FHIRCommonLanguages|string|null $value = null,
    ) {
    }
}
