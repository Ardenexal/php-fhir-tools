<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationDefinition;

/**
 * @description Multiple  ranges of results qualified by different contexts for ordinal or continuous observations conforming to this ObservationDefinition.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ObservationDefinition', elementPath: 'ObservationDefinition.qualifiedInterval', fhirVersion: 'R4')]
class ObservationDefinitionQualifiedInterval extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ObservationRangeCategoryType category reference | critical | absolute */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ObservationRangeCategoryType $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range range The interval itself, for continuous or ordinal observations */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range $range = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept context Range context qualifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $context = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> appliesTo Targetted population of the range */
		public array $appliesTo = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AdministrativeGenderType gender male | female | other | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AdministrativeGenderType $gender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range age Applicable age range, if relevant */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range $age = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range gestationalAge Applicable gestational age range, if relevant */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Range $gestationalAge = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string condition Condition associated with the reference range */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $condition = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
