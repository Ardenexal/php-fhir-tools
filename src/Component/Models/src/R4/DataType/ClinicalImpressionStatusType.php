<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ClinicalImpressionStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ClinicalImpressionStatus
 *
 * @description Code type wrapper for ClinicalImpressionStatus enum
 */
class ClinicalImpressionStatusType extends CodePrimitive
{
    public function __construct(
        /** @param ClinicalImpressionStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
