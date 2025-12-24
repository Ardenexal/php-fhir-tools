<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRArtifactAssessmentDisposition;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRArtifactAssessmentDisposition
 *
 * @description Code type wrapper for FHIRArtifactAssessmentDisposition enum
 */
class FHIRFHIRArtifactAssessmentDispositionType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRArtifactAssessmentDisposition|string|null $value The code value */
        public FHIRFHIRArtifactAssessmentDisposition|string|null $value = null,
    ) {
    }
}
