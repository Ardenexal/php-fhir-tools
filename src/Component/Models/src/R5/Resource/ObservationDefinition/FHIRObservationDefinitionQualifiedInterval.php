<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Multiple  ranges of results qualified by different contexts for ordinal or continuous observations conforming to this ObservationDefinition.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ObservationDefinition', elementPath: 'ObservationDefinition.qualifiedInterval', fhirVersion: 'R4B')]
class FHIRObservationDefinitionQualifiedInterval extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRObservationRangeCategoryType category reference | critical | absolute */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRObservationRangeCategoryType $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange range The interval itself, for continuous or ordinal observations */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange $range = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept context Range context qualifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $context = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> appliesTo Targetted population of the range */
		public array $appliesTo = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAdministrativeGenderType gender male | female | other | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAdministrativeGenderType $gender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange age Applicable age range, if relevant */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange $age = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange gestationalAge Applicable gestational age range, if relevant */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange $gestationalAge = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string condition Condition associated with the reference range */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $condition = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
