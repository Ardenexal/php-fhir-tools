<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ConceptMapEquivalence;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

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
