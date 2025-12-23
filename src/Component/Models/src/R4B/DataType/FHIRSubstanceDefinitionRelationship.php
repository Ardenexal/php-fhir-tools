<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element SubstanceDefinition.relationship
 * @description A link between this substance and another, with details of the relationship.
 */
class FHIRSubstanceDefinitionRelationship extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept substanceDefinitionX A pointer to another substance, as a resource or a representational code */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|null $substanceDefinitionX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept type For example "salt to parent", "active moiety" */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean isDefining For example where an enzyme strongly bonds with a particular substance, this is a defining relationship for that enzyme, out of several possible relationships */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $isDefining = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string amountX A numeric factor for the relationship, e.g. that a substance salt has some percentage of active substance in relation to some other */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $amountX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRatio ratioHighLimitAmount For use when the numeric has an uncertain range */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRatio $ratioHighLimitAmount = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept comparator An operator for the amount, for example "average", "approximately", "less than" */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $comparator = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> source Supporting literature */
		public array $source = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
