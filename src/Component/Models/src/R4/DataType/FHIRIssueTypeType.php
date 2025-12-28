<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRIssueType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRIssueType
 *
 * @description Code type wrapper for FHIRIssueType enum
 */
class FHIRIssueTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRIssueType|string|null $value The code value */
        public FHIRIssueType|string|null $value = null,
    ) {
    }
}
