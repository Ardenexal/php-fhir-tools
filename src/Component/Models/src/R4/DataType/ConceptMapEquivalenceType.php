<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ConceptMapEquivalence;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ConceptMapEquivalence
 *
 * @description Code type wrapper for ConceptMapEquivalence enum
 */
class ConceptMapEquivalenceType extends CodePrimitive
{
    public function __construct(
        /** @param ConceptMapEquivalence|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
