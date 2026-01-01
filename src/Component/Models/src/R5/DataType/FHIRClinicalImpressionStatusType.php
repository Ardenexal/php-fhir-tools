<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRClinicalImpressionStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRClinicalImpressionStatus
 *
 * @description Code type wrapper for FHIRClinicalImpressionStatus enum
 */
class FHIRClinicalImpressionStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRClinicalImpressionStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
