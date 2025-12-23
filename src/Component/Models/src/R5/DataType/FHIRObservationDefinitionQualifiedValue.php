<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ObservationDefinition.qualifiedValue
 * @description A set of qualified values associated with a context and a set of conditions -  provides a range for quantitative and ordinal observations and a collection of value sets for qualitative observations.
 */
class FHIRObservationDefinitionQualifiedValue extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept context Context qualifier for the set of qualified values */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $context = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> appliesTo Targetted population for the set of qualified values */
		public array $appliesTo = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAdministrativeGenderType gender male | female | other | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAdministrativeGenderType $gender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange age Applicable age range for the set of qualified values */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange $age = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange gestationalAge Applicable gestational age range for the set of qualified values */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange $gestationalAge = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string condition Condition associated with the set of qualified values */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $condition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRObservationRangeCategoryType rangeCategory reference | critical | absolute */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRObservationRangeCategoryType $rangeCategory = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange range The range for continuous or ordinal observations */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRange $range = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical validCodedValueSet Value set of valid coded values as part of this set of qualified values */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $validCodedValueSet = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical normalCodedValueSet Value set of normal coded values as part of this set of qualified values */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $normalCodedValueSet = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical abnormalCodedValueSet Value set of abnormal coded values as part of this set of qualified values */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $abnormalCodedValueSet = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical criticalCodedValueSet Value set of critical coded values as part of this set of qualified values */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $criticalCodedValueSet = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
