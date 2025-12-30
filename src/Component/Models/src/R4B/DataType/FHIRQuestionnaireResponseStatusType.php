<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRQuestionnaireResponseStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRQuestionnaireResponseStatus
 *
 * @description Code type wrapper for FHIRQuestionnaireResponseStatus enum
 */
class FHIRQuestionnaireResponseStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRQuestionnaireResponseStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
