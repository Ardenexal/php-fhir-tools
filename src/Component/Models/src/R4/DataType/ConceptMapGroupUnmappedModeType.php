<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ConceptMapGroupUnmappedMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ConceptMapGroupUnmappedMode
 *
 * @description Code type wrapper for ConceptMapGroupUnmappedMode enum
 */
class ConceptMapGroupUnmappedModeType extends CodePrimitive
{
    public function __construct(
        /** @param ConceptMapGroupUnmappedMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
