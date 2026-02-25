<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\ClinicalUseDefinitionType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type ClinicalUseDefinitionType
 *
 * @description Code type wrapper for ClinicalUseDefinitionType enum
 */
class ClinicalUseDefinitionTypeType extends CodePrimitive
{
    public function __construct(
        /** @param ClinicalUseDefinitionType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
