<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStructureDefinitionKind;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRStructureDefinitionKind
 *
 * @description Code type wrapper for FHIRStructureDefinitionKind enum
 */
class FHIRFHIRStructureDefinitionKindType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRStructureDefinitionKind|string|null $value The code value */
        public FHIRFHIRStructureDefinitionKind|string|null $value = null,
    ) {
    }
}
