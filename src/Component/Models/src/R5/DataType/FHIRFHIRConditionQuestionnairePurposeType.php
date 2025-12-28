<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRConditionQuestionnairePurpose;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConditionQuestionnairePurpose
 *
 * @description Code type wrapper for FHIRConditionQuestionnairePurpose enum
 */
class FHIRFHIRConditionQuestionnairePurposeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRConditionQuestionnairePurpose|string|null $value The code value */
        public FHIRFHIRConditionQuestionnairePurpose|string|null $value = null,
    ) {
    }
}
