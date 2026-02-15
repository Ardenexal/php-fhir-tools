<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates that the element is sliced into a set of alternative definitions (i.e. in a structure definition, there are multiple different constraints on a single element in the base resource). Slicing can be used in any resource that has cardinality ..* on the base resource, or any resource with a choice of types. The set of slices is any elements that come after this in the element sequence that have the same path, until a shorter path occurs (the shorter path terminates the set).
 */
#[FHIRComplexType(typeName: 'ElementDefinition.slicing', fhirVersion: 'R4')]
class ElementDefinitionSlicing extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<ElementDefinitionSlicingDiscriminator> discriminator Element values that are used to distinguish the slices */
        public array $discriminator = [],
        /** @var StringPrimitive|string|null description Text description of how slicing works (or not) */
        public StringPrimitive|string|null $description = null,
        /** @var bool|null ordered If elements must be in same order as slices */
        public ?bool $ordered = null,
        /** @var SlicingRulesType|null rules closed | open | openAtEnd */
        #[NotBlank]
        public ?SlicingRulesType $rules = null,
    ) {
        parent::__construct($id, $extension);
    }
}
