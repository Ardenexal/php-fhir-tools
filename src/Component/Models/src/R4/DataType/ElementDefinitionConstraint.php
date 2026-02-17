<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Formal constraints such as co-occurrence and other constraints that can be computationally evaluated within the context of the instance.
 */
#[FHIRComplexType(typeName: 'ElementDefinition.constraint', fhirVersion: 'R4')]
class ElementDefinitionConstraint extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var IdPrimitive|null key Target of 'condition' reference above */
        #[NotBlank]
        public ?IdPrimitive $key = null,
        /** @var StringPrimitive|string|null requirements Why this constraint is necessary or appropriate */
        public StringPrimitive|string|null $requirements = null,
        /** @var ConstraintSeverityType|null severity error | warning */
        #[NotBlank]
        public ?ConstraintSeverityType $severity = null,
        /** @var StringPrimitive|string|null human Human description of constraint */
        #[NotBlank]
        public StringPrimitive|string|null $human = null,
        /** @var StringPrimitive|string|null expression FHIRPath expression of constraint */
        public StringPrimitive|string|null $expression = null,
        /** @var StringPrimitive|string|null xpath XPath expression of constraint */
        public StringPrimitive|string|null $xpath = null,
        /** @var CanonicalPrimitive|null source Reference to original source of constraint */
        public ?CanonicalPrimitive $source = null,
    ) {
        parent::__construct($id, $extension);
    }
}
