<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDetectedIssueStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRDetectedIssueStatus
 *
 * @description Code type wrapper for FHIRDetectedIssueStatus enum
 */
class FHIRFHIRDetectedIssueStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDetectedIssueStatus|string|null $value The code value */
        public FHIRFHIRDetectedIssueStatus|string|null $value = null,
    ) {
    }
}
