<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRRelatedArtifactType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRRelatedArtifactType
 *
 * @description Code type wrapper for FHIRRelatedArtifactType enum
 */
class FHIRRelatedArtifactTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRRelatedArtifactType|string|null $value The code value */
        public FHIRRelatedArtifactType|string|null $value = null,
    ) {
    }
}
