<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRQuestionnaireItemType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRQuestionnaireItemType
 *
 * @description Code type wrapper for FHIRQuestionnaireItemType enum
 */
class FHIRQuestionnaireItemTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRQuestionnaireItemType|string|null $value The code value */
        public FHIRQuestionnaireItemType|string|null $value = null,
    ) {
    }
}
