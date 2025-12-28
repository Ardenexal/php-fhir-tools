<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRArtifactAssessmentDisposition;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRArtifactAssessmentDisposition
 *
 * @description Code type wrapper for FHIRArtifactAssessmentDisposition enum
 */
class FHIRArtifactAssessmentDispositionType extends FHIRCode
{
    public function __construct(
        /** @var FHIRArtifactAssessmentDisposition|string|null $value The code value */
        public FHIRArtifactAssessmentDisposition|string|null $value = null,
    ) {
    }
}
