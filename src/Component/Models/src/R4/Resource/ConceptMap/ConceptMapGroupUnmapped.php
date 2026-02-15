<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ConceptMap;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ConceptMapGroupUnmappedModeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description What to do when there is no mapping for the source concept. "Unmapped" does not include codes that are unmatched, and the unmapped element is ignored in a code is specified to have equivalence = unmatched.
 */
#[FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.unmapped', fhirVersion: 'R4')]
class ConceptMapGroupUnmapped extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var ConceptMapGroupUnmappedModeType|null mode provided | fixed | other-map */
        #[NotBlank]
        public ?ConceptMapGroupUnmappedModeType $mode = null,
        /** @var CodePrimitive|null code Fixed code when mode = fixed */
        public ?CodePrimitive $code = null,
        /** @var StringPrimitive|string|null display Display for the code */
        public StringPrimitive|string|null $display = null,
        /** @var CanonicalPrimitive|null url canonical reference to an additional ConceptMap to use for mapping if the source concept is unmapped */
        public ?CanonicalPrimitive $url = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
