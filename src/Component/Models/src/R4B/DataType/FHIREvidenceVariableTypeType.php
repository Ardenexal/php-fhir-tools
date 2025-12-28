<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREvidenceVariableType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREvidenceVariableType
 *
 * @description Code type wrapper for FHIREvidenceVariableType enum
 */
class FHIREvidenceVariableTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIREvidenceVariableType|string|null $value The code value */
        public FHIREvidenceVariableType|string|null $value = null,
    ) {
    }
}
