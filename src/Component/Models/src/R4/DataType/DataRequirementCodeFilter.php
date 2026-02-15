<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Code filters specify additional constraints on the data, specifying the value set of interest for a particular element of the data. Each code filter defines an additional constraint on the data, i.e. code filters are AND'ed, not OR'ed.
 */
#[FHIRComplexType(typeName: 'DataRequirement.codeFilter', fhirVersion: 'R4')]
class DataRequirementCodeFilter extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var StringPrimitive|string|null path A code-valued attribute to filter on */
        public StringPrimitive|string|null $path = null,
        /** @var StringPrimitive|string|null searchParam A coded (token) parameter to search on */
        public StringPrimitive|string|null $searchParam = null,
        /** @var CanonicalPrimitive|null valueSet Valueset for the filter */
        public ?CanonicalPrimitive $valueSet = null,
        /** @var array<Coding> code What code is expected */
        public array $code = [],
    ) {
        parent::__construct($id, $extension);
    }
}
