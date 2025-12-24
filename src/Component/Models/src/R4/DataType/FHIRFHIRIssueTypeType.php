<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRIssueType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRIssueType
 *
 * @description Code type wrapper for FHIRIssueType enum
 */
class FHIRFHIRIssueTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRIssueType|string|null $value The code value */
        public FHIRFHIRIssueType|string|null $value = null,
    ) {
    }
}
