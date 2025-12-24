<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRIssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRIssueSeverity
 *
 * @description Code type wrapper for FHIRIssueSeverity enum
 */
class FHIRFHIRIssueSeverityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRIssueSeverity|string|null $value The code value */
        public FHIRFHIRIssueSeverity|string|null $value = null,
    ) {
    }
}
