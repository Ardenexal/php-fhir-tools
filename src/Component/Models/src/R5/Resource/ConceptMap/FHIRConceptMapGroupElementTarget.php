<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRConceptMapRelationshipType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A concept from the target value set that this concept maps to.
 */
#[FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.element.target', fhirVersion: 'R5')]
class FHIRConceptMapGroupElementTarget extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null code Code that identifies the target element */
        public ?FHIRCode $code = null,
        /** @var FHIRString|string|null display Display for the code */
        public FHIRString|string|null $display = null,
        /** @var FHIRCanonical|null valueSet Identifies the set of target concepts */
        public ?FHIRCanonical $valueSet = null,
        /** @var FHIRConceptMapRelationshipType|null relationship related-to | equivalent | source-is-narrower-than-target | source-is-broader-than-target | not-related-to */
        #[NotBlank]
        public ?FHIRConceptMapRelationshipType $relationship = null,
        /** @var FHIRString|string|null comment Description of status/issues in mapping */
        public FHIRString|string|null $comment = null,
        /** @var array<FHIRConceptMapGroupElementTargetProperty> property Property value for the source -> target mapping */
        public array $property = [],
        /** @var array<FHIRConceptMapGroupElementTargetDependsOn> dependsOn Other properties required for this mapping */
        public array $dependsOn = [],
        /** @var array<FHIRConceptMapGroupElementTargetDependsOn> product Other data elements that this mapping also produces */
        public array $product = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
