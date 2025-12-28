<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRDetectedIssueStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDetectedIssueStatus
 *
 * @description Code type wrapper for FHIRDetectedIssueStatus enum
 */
class FHIRDetectedIssueStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDetectedIssueStatus|string|null $value The code value */
        public FHIRDetectedIssueStatus|string|null $value = null,
    ) {
    }
}
