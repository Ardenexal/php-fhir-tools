<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRQuestionnaireItemDisabledDisplay;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRQuestionnaireItemDisabledDisplay
 *
 * @description Code type wrapper for FHIRQuestionnaireItemDisabledDisplay enum
 */
class FHIRQuestionnaireItemDisabledDisplayType extends FHIRCode
{
    public function __construct(
        /** @var FHIRQuestionnaireItemDisabledDisplay|string|null $value The code value */
        public FHIRQuestionnaireItemDisabledDisplay|string|null $value = null,
    ) {
    }
}
