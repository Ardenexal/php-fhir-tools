<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A concept from the target value set that this concept maps to.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.element.target', fhirVersion: 'R4')]
class FHIRConceptMapGroupElementTarget extends FHIRBackboneElement
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
        /** @var FHIRConceptMapEquivalenceType|null equivalence relatedto | equivalent | equal | wider | subsumes | narrower | specializes | inexact | unmatched | disjoint */
        #[NotBlank]
        public ?FHIRConceptMapEquivalenceType $equivalence = null,
        /** @var FHIRString|string|null comment Description of status/issues in mapping */
        public FHIRString|string|null $comment = null,
        /** @var array<FHIRConceptMapGroupElementTargetDependsOn> dependsOn Other elements required for this mapping (from context) */
        public array $dependsOn = [],
        /** @var array<FHIRConceptMapGroupElementTargetDependsOn> product Other concepts that this mapping also produces */
        public array $product = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
