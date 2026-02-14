<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Specifies a concept to be included or excluded.
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.compose.include.concept', fhirVersion: 'R4')]
class ValueSetComposeIncludeConcept extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodePrimitive|null code Code or expression from system */
        #[NotBlank]
        public ?CodePrimitive $code = null,
        /** @var StringPrimitive|string|null display Text to display for this code for this value set in this valueset */
        public StringPrimitive|string|null $display = null,
        /** @var array<ValueSetComposeIncludeConceptDesignation> designation Additional representations for this concept */
        public array $designation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
