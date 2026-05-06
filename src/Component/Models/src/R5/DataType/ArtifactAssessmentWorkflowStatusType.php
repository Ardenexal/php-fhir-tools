<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\ArtifactAssessmentWorkflowStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type ArtifactAssessmentWorkflowStatus
 *
 * @description Code type wrapper for ArtifactAssessmentWorkflowStatus enum
 */
class ArtifactAssessmentWorkflowStatusType extends CodePrimitive
{
    public function __construct(
        /** @param ArtifactAssessmentWorkflowStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
