<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Mappings for an individual concept in the source to one or more concepts in the target.
 */
#[FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.element', fhirVersion: 'R5')]
class FHIRConceptMapGroupElement extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null code Identifies element being mapped */
        public ?\FHIRCode $code = null,
        /** @var FHIRString|string|null display Display for the code */
        public \FHIRString|string|null $display = null,
        /** @var FHIRCanonical|null valueSet Identifies the set of concepts being mapped */
        public ?\FHIRCanonical $valueSet = null,
        /** @var FHIRBoolean|null noMap No mapping to a target concept for this source concept */
        public ?\FHIRBoolean $noMap = null,
        /** @var array<FHIRConceptMapGroupElementTarget> target Concept in target system for element */
        public array $target = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
