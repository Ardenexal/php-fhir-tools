<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A link between this substance and another, with details of the relationship.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.relationship', fhirVersion: 'R5')]
class FHIRSubstanceSpecificationRelationship extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept substanceX A pointer to another substance, as a resource or just a representational code */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|null $substanceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept relationship For example "salt to parent", "active moiety", "starting material" */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $relationship = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean isDefining For example where an enzyme strongly bonds with a particular substance, this is a defining relationship for that enzyme, out of several possible substance relationships */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $isDefining = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string amountX A numeric factor for the relationship, for instance to express that the salt of a substance has some percentage of the active substance in relation to some other */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $amountX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio amountRatioLowLimit For use when the numeric */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio $amountRatioLowLimit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept amountType An operator for the amount, for example "average", "approximately", "less than" */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $amountType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> source Supporting literature */
		public array $source = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
