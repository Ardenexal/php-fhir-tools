<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\DetectedIssueStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type DetectedIssueStatus
 *
 * @description Code type wrapper for DetectedIssueStatus enum
 */
class DetectedIssueStatusType extends CodePrimitive
{
    public function __construct(
        /** @param DetectedIssueStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
