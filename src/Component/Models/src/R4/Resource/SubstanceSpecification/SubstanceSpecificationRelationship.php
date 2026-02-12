<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification;

/**
 * @description A link between this substance and another, with details of the relationship.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.relationship', fhirVersion: 'R4')]
class SubstanceSpecificationRelationship extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept substanceX A pointer to another substance, as a resource or just a representational code */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|null $substanceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept relationship For example "salt to parent", "active moiety", "starting material" */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $relationship = null,
		/** @var null|bool isDefining For example where an enzyme strongly bonds with a particular substance, this is a defining relationship for that enzyme, out of several possible substance relationships */
		public ?bool $isDefining = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string amountX A numeric factor for the relationship, for instance to express that the salt of a substance has some percentage of the active substance in relation to some other */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $amountX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio amountRatioLowLimit For use when the numeric */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio $amountRatioLowLimit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept amountType An operator for the amount, for example "average", "approximately", "less than" */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $amountType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> source Supporting literature */
		public array $source = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
