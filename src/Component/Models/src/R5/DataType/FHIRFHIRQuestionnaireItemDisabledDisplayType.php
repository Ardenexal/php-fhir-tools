<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRQuestionnaireItemDisabledDisplay;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRQuestionnaireItemDisabledDisplay
 *
 * @description Code type wrapper for FHIRQuestionnaireItemDisabledDisplay enum
 */
class FHIRFHIRQuestionnaireItemDisabledDisplayType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRQuestionnaireItemDisabledDisplay|string|null $value The code value */
        public FHIRFHIRQuestionnaireItemDisabledDisplay|string|null $value = null,
    ) {
    }
}
