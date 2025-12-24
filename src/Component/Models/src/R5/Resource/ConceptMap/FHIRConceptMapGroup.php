<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;

/**
 * @description A group of mappings that all have the same source and target system.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group', fhirVersion: 'R5')]
class FHIRConceptMapGroup extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCanonical|null source Source system where concepts to be mapped are defined */
        public ?FHIRCanonical $source = null,
        /** @var FHIRCanonical|null target Target system that the concepts are to be mapped to */
        public ?FHIRCanonical $target = null,
        /** @var array<FHIRConceptMapGroupElement> element Mappings for a concept from the source set */
        public array $element = [],
        /** @var FHIRConceptMapGroupUnmapped|null unmapped What to do when there is no mapping target for the source concept and ConceptMap.group.element.noMap is not true */
        public ?FHIRConceptMapGroupUnmapped $unmapped = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
