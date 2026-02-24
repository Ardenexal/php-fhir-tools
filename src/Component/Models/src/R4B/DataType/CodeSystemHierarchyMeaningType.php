<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\CodeSystemHierarchyMeaning;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type CodeSystemHierarchyMeaning
 *
 * @description Code type wrapper for CodeSystemHierarchyMeaning enum
 */
class CodeSystemHierarchyMeaningType extends CodePrimitive
{
    public function __construct(
        /** @param CodeSystemHierarchyMeaning|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
