<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A concept from the target value set that this concept maps to.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.element.target', fhirVersion: 'R5')]
class FHIRConceptMapGroupElementTarget extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode code Code that identifies the target element */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string display Display for the code */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $display = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical valueSet Identifies the set of target concepts */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $valueSet = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRConceptMapRelationshipType relationship related-to | equivalent | source-is-narrower-than-target | source-is-broader-than-target | not-related-to */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRConceptMapRelationshipType $relationship = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string comment Description of status/issues in mapping */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $comment = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConceptMapGroupElementTargetProperty> property Property value for the source -> target mapping */
		public array $property = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConceptMapGroupElementTargetDependsOn> dependsOn Other properties required for this mapping */
		public array $dependsOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConceptMapGroupElementTargetDependsOn> product Other data elements that this mapping also produces */
		public array $product = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
