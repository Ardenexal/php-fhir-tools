<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\ArtifactAssessmentDisposition;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type ArtifactAssessmentDisposition
 *
 * @description Code type wrapper for ArtifactAssessmentDisposition enum
 */
class ArtifactAssessmentDispositionType extends CodePrimitive
{
    public function __construct(
        /** @param ArtifactAssessmentDisposition|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
