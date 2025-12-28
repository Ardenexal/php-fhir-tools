<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRDetectedIssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDetectedIssueSeverity
 *
 * @description Code type wrapper for FHIRDetectedIssueSeverity enum
 */
class FHIRDetectedIssueSeverityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDetectedIssueSeverity|string|null $value The code value */
        public FHIRDetectedIssueSeverity|string|null $value = null,
    ) {
    }
}
