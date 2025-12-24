<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRQuestionnaireItemType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRQuestionnaireItemType
 *
 * @description Code type wrapper for FHIRQuestionnaireItemType enum
 */
class FHIRFHIRQuestionnaireItemTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRQuestionnaireItemType|string|null $value The code value */
        public FHIRFHIRQuestionnaireItemType|string|null $value = null,
    ) {
    }
}
