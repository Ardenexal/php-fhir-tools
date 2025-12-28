<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRRelatedArtifactType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRRelatedArtifactType
 *
 * @description Code type wrapper for FHIRRelatedArtifactType enum
 */
class FHIRFHIRRelatedArtifactTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRRelatedArtifactType|string|null $value The code value */
        public FHIRFHIRRelatedArtifactType|string|null $value = null,
    ) {
    }
}
