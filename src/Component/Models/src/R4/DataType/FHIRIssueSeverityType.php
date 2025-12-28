<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRIssueSeverity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRIssueSeverity
 *
 * @description Code type wrapper for FHIRIssueSeverity enum
 */
class FHIRIssueSeverityType extends FHIRCode
{
    public function __construct(
        /** @var FHIRIssueSeverity|string|null $value The code value */
        public FHIRIssueSeverity|string|null $value = null,
    ) {
    }
}
