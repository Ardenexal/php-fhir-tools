<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CodeSystem;

/**
 * @description Concepts that are in the code system. The concept definitions are inherently hierarchical, but the definitions must be consulted to determine what the meanings of the hierarchical relationships are.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CodeSystem', elementPath: 'CodeSystem.concept', fhirVersion: 'R4')]
class CodeSystemConcept extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive code Code that identifies concept */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string display Text to display to the user */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $display = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string definition Formal definition */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $definition = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CodeSystem\CodeSystemConceptDesignation> designation Additional representations for the concept */
		public array $designation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CodeSystem\CodeSystemConceptProperty> property Property value for the concept */
		public array $property = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CodeSystem\CodeSystemConcept> concept Child Concepts (is-a/contains/categorizes) */
		public array $concept = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
