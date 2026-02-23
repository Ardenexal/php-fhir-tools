<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/DataRequirement
 * @description Describes a required data item for evaluation in terms of the type of data, and optional code or date-based filters of the data.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'DataRequirement', fhirVersion: 'R4B')]
class DataRequirement extends Element
{
	public const FHIR_PROPERTY_MAP = [
		'id' => [
			'fhirType' => 'http://hl7.org/fhirpath/System.String',
			'propertyKind' => 'scalar',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'extension' => [
			'fhirType' => 'Extension',
			'propertyKind' => 'extension',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'type' => [
			'fhirType' => 'code',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'profile' => [
			'fhirType' => 'canonical',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'subjectX' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => true,
			'jsonKey' => null,
			'variants' => [
				[
					'fhirType' => 'CodeableConcept',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
					'jsonKey' => 'subjectCodeableConcept',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Reference',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
					'jsonKey' => 'subjectReference',
					'isBuiltin' => false,
				],
			],
		],
		'mustSupport' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'codeFilter' => [
			'fhirType' => 'Element',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'dateFilter' => [
			'fhirType' => 'Element',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'limit' => [
			'fhirType' => 'positiveInt',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'sort' => [
			'fhirType' => 'Element',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
	];

	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension> extension Additional content defined by implementations */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAllTypesType type The type of the required data */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), \Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRAllTypesType $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive> profile The profile of the required data */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true)]
		public array $profile = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference subjectX E.g. Patient, Practitioner, RelatedPerson, Organization, Location, Device */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'CodeableConcept',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
				'jsonKey' => 'subjectCodeableConcept',
			],
			[
				'fhirType' => 'Reference',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
				'jsonKey' => 'subjectReference',
			],
		],
		)]
		public CodeableConcept|Reference|null $subjectX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string> mustSupport Indicates specific structure elements that are referenced by the knowledge module */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
		public array $mustSupport = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\DataRequirementCodeFilter> codeFilter What codes are expected */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Element', propertyKind: 'complex', isArray: true)]
		public array $codeFilter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\DataRequirementDateFilter> dateFilter What dates/date ranges are expected */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Element', propertyKind: 'complex', isArray: true)]
		public array $dateFilter = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive limit Number of results */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive $limit = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\DataRequirementSort> sort Order of the results */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Element', propertyKind: 'complex', isArray: true)]
		public array $sort = [],
	) {
		parent::__construct($id, $extension);
	}
}
