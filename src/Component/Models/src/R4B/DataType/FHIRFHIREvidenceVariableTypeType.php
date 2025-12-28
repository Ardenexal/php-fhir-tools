<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREvidenceVariableType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREvidenceVariableType
 *
 * @description Code type wrapper for FHIREvidenceVariableType enum
 */
class FHIRFHIREvidenceVariableTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIREvidenceVariableType|string|null $value The code value */
        public FHIRFHIREvidenceVariableType|string|null $value = null,
    ) {
    }
}
