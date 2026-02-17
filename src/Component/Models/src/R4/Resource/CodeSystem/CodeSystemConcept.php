<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CodeSystem;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Concepts that are in the code system. The concept definitions are inherently hierarchical, but the definitions must be consulted to determine what the meanings of the hierarchical relationships are.
 */
#[FHIRBackboneElement(parentResource: 'CodeSystem', elementPath: 'CodeSystem.concept', fhirVersion: 'R4')]
class CodeSystemConcept extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodePrimitive|null code Code that identifies concept */
        #[NotBlank]
        public ?CodePrimitive $code = null,
        /** @var StringPrimitive|string|null display Text to display to the user */
        public StringPrimitive|string|null $display = null,
        /** @var StringPrimitive|string|null definition Formal definition */
        public StringPrimitive|string|null $definition = null,
        /** @var array<CodeSystemConceptDesignation> designation Additional representations for the concept */
        public array $designation = [],
        /** @var array<CodeSystemConceptProperty> property Property value for the concept */
        public array $property = [],
        /** @var array<CodeSystemConcept> concept Child Concepts (is-a/contains/categorizes) */
        public array $concept = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
