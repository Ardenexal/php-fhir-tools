<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description What to do when there is no mapping to a target concept from the source concept and ConceptMap.group.element.noMap is not true. This provides the "default" to be applied when there is no target concept mapping specified or the expansion of ConceptMap.group.element.target.valueSet is empty.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.unmapped', fhirVersion: 'R5')]
class FHIRConceptMapGroupUnmapped extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRConceptMapGroupUnmappedModeType|null mode use-source-code | fixed | other-map */
        #[NotBlank]
        public ?FHIRConceptMapGroupUnmappedModeType $mode = null,
        /** @var FHIRCode|null code Fixed code when mode = fixed */
        public ?FHIRCode $code = null,
        /** @var FHIRString|string|null display Display for the code */
        public FHIRString|string|null $display = null,
        /** @var FHIRCanonical|null valueSet Fixed code set when mode = fixed */
        public ?FHIRCanonical $valueSet = null,
        /** @var FHIRConceptMapRelationshipType|null relationship related-to | equivalent | source-is-narrower-than-target | source-is-broader-than-target | not-related-to */
        public ?FHIRConceptMapRelationshipType $relationship = null,
        /** @var FHIRCanonical|null otherMap canonical reference to an additional ConceptMap to use for mapping if the source concept is unmapped */
        public ?FHIRCanonical $otherMap = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
