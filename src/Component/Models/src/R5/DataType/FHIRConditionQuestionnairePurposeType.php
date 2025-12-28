<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRConditionQuestionnairePurpose;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConditionQuestionnairePurpose
 *
 * @description Code type wrapper for FHIRConditionQuestionnairePurpose enum
 */
class FHIRConditionQuestionnairePurposeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRConditionQuestionnairePurpose|string|null $value The code value */
        public FHIRConditionQuestionnairePurpose|string|null $value = null,
    ) {
    }
}
