<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRRelatedArtifactTypeExpanded;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRRelatedArtifactTypeExpanded
 *
 * @description Code type wrapper for FHIRRelatedArtifactTypeExpanded enum
 */
class FHIRFHIRRelatedArtifactTypeExpandedType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRRelatedArtifactTypeExpanded|string|null $value The code value */
        public FHIRFHIRRelatedArtifactTypeExpanded|string|null $value = null,
    ) {
    }
}
