<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ConceptMap;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ConceptMapEquivalenceType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A concept from the target value set that this concept maps to.
 */
#[FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.element.target', fhirVersion: 'R4')]
class ConceptMapGroupElementTarget extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodePrimitive|null code Code that identifies the target element */
        public ?CodePrimitive $code = null,
        /** @var StringPrimitive|string|null display Display for the code */
        public StringPrimitive|string|null $display = null,
        /** @var ConceptMapEquivalenceType|null equivalence relatedto | equivalent | equal | wider | subsumes | narrower | specializes | inexact | unmatched | disjoint */
        #[NotBlank]
        public ?ConceptMapEquivalenceType $equivalence = null,
        /** @var StringPrimitive|string|null comment Description of status/issues in mapping */
        public StringPrimitive|string|null $comment = null,
        /** @var array<ConceptMapGroupElementTargetDependsOn> dependsOn Other elements required for this mapping (from context) */
        public array $dependsOn = [],
        /** @var array<ConceptMapGroupElementTargetDependsOn> product Other concepts that this mapping also produces */
        public array $product = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
