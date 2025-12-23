<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element ObservationDefinition.qualifiedInterval
 * @description Multiple  ranges of results qualified by different contexts for ordinal or continuous observations conforming to this ObservationDefinition.
 */
class FHIRObservationDefinitionQualifiedInterval extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRObservationRangeCategoryType category reference | critical | absolute */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRObservationRangeCategoryType $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange range The interval itself, for continuous or ordinal observations */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange $range = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept context Range context qualifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $context = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> appliesTo Targetted population of the range */
		public array $appliesTo = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAdministrativeGenderType gender male | female | other | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAdministrativeGenderType $gender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange age Applicable age range, if relevant */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange $age = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange gestationalAge Applicable gestational age range, if relevant */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange $gestationalAge = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string condition Condition associated with the reference range */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $condition = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
