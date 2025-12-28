<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRDetectedIssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDetectedIssueSeverity
 *
 * @description Code type wrapper for FHIRDetectedIssueSeverity enum
 */
class FHIRFHIRDetectedIssueSeverityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDetectedIssueSeverity|string|null $value The code value */
        public FHIRFHIRDetectedIssueSeverity|string|null $value = null,
    ) {
    }
}
