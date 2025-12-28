<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRQuestionnaireResponseStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRQuestionnaireResponseStatus
 *
 * @description Code type wrapper for FHIRQuestionnaireResponseStatus enum
 */
class FHIRFHIRQuestionnaireResponseStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRQuestionnaireResponseStatus|string|null $value The code value */
        public FHIRFHIRQuestionnaireResponseStatus|string|null $value = null,
    ) {
    }
}
