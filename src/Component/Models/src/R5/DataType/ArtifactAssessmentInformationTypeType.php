<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\ArtifactAssessmentInformationType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type ArtifactAssessmentInformationType
 *
 * @description Code type wrapper for ArtifactAssessmentInformationType enum
 */
class ArtifactAssessmentInformationTypeType extends CodePrimitive
{
    public function __construct(
        /** @param ArtifactAssessmentInformationType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
