<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\EvidenceVariableType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type EvidenceVariableType
 *
 * @description Code type wrapper for EvidenceVariableType enum
 */
class EvidenceVariableTypeType extends CodePrimitive
{
    public function __construct(
        /** @param EvidenceVariableType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
