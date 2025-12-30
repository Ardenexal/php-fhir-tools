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
        /** @param FHIRDetectedIssueStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
