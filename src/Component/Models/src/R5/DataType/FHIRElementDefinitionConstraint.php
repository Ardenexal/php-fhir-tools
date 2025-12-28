<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Formal constraints such as co-occurrence and other constraints that can be computationally evaluated within the context of the instance.
 */
#[FHIRComplexType(typeName: 'ElementDefinition.constraint', fhirVersion: 'R5')]
class FHIRElementDefinitionConstraint extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRId|null key Target of 'condition' reference above */
        #[NotBlank]
        public ?\FHIRId $key = null,
        /** @var FHIRMarkdown|null requirements Why this constraint is necessary or appropriate */
        public ?\FHIRMarkdown $requirements = null,
        /** @var FHIRConstraintSeverityType|null severity error | warning */
        #[NotBlank]
        public ?\FHIRConstraintSeverityType $severity = null,
        /** @var FHIRBoolean|null suppress Suppress warning or hint in profile */
        public ?\FHIRBoolean $suppress = null,
        /** @var FHIRString|string|null human Human description of constraint */
        #[NotBlank]
        public \FHIRString|string|null $human = null,
        /** @var FHIRString|string|null expression FHIRPath expression of constraint */
        public \FHIRString|string|null $expression = null,
        /** @var FHIRCanonical|null source Reference to original source of constraint */
        public ?\FHIRCanonical $source = null,
    ) {
        parent::__construct($id, $extension);
    }
}
