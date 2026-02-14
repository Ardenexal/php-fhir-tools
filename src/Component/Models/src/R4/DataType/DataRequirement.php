<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/DataRequirement
 * @description Describes a required data item for evaluation in terms of the type of data, and optional code or date-based filters of the data.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'DataRequirement', fhirVersion: 'R4')]
class DataRequirement extends Element
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAllTypesType type The type of the required data */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRAllTypesType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive> profile The profile of the required data */
		public array $profile = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subjectX E.g. Patient, Practitioner, RelatedPerson, Organization, Location, Device */
		public CodeableConcept|Reference|null $subjectX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> mustSupport Indicates specific structure elements that are referenced by the knowledge module */
		public array $mustSupport = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirementCodeFilter> codeFilter What codes are expected */
		public array $codeFilter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirementDateFilter> dateFilter What dates/date ranges are expected */
		public array $dateFilter = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive limit Number of results */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive $limit = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirementSort> sort Order of the results */
		public array $sort = [],
	) {
		parent::__construct($id, $extension);
	}
}
