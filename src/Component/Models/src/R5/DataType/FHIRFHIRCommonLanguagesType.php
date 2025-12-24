<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRCommonLanguages;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRCommonLanguages
 *
 * @description Code type wrapper for FHIRCommonLanguages enum
 */
class FHIRFHIRCommonLanguagesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCommonLanguages|string|null $value The code value */
        public FHIRFHIRCommonLanguages|string|null $value = null,
    ) {
    }
}
