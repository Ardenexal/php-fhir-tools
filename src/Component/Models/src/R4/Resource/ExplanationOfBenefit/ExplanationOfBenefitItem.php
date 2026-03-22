<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit;

/**
 * @description A claim line. Either a simple (a product or service) or a 'group' of details which can also be a simple items or groups of sub-details.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement(parentResource: 'ExplanationOfBenefit', elementPath: 'ExplanationOfBenefit.item', fhirVersion: 'R4')]
class ExplanationOfBenefitItem extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public const FHIR_PROPERTY_MAP = [
		'id' => [
			'fhirType' => 'http://hl7.org/fhirpath/System.String',
			'propertyKind' => 'scalar',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
			'xmlSerializedName' => '@id',
		],
		'extension' => [
			'fhirType' => 'Extension',
			'propertyKind' => 'extension',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'modifierExtension' => [
			'fhirType' => 'Extension',
			'propertyKind' => 'modifierExtension',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'sequence' => [
			'fhirType' => 'positiveInt',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'careTeamSequence' => [
			'fhirType' => 'positiveInt',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'diagnosisSequence' => [
			'fhirType' => 'positiveInt',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'procedureSequence' => [
			'fhirType' => 'positiveInt',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'informationSequence' => [
			'fhirType' => 'positiveInt',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'revenue' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'category' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'productOrService' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'modifier' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
			'variants' => null,
		],
		'programCode' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
			'variants' => null,
		],
		'serviced' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => true,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => [
				[
					'fhirType' => 'date',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive',
					'jsonKey' => 'servicedDate',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Period',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
					'jsonKey' => 'servicedPeriod',
					'isBuiltin' => false,
				],
			],
		],
		'location' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => true,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => [
				[
					'fhirType' => 'CodeableConcept',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
					'jsonKey' => 'locationCodeableConcept',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Address',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Address',
					'jsonKey' => 'locationAddress',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Reference',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
					'jsonKey' => 'locationReference',
					'isBuiltin' => false,
				],
			],
		],
		'quantity' => [
			'fhirType' => 'Quantity',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'unitPrice' => [
			'fhirType' => 'Money',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'factor' => [
			'fhirType' => 'decimal',
			'propertyKind' => 'scalar',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'net' => [
			'fhirType' => 'Money',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'udi' => [
			'fhirType' => 'Reference',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
			'variants' => null,
		],
		'bodySite' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'subSite' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
			'variants' => null,
		],
		'encounter' => [
			'fhirType' => 'Reference',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
			'variants' => null,
		],
		'noteNumber' => [
			'fhirType' => 'positiveInt',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'adjudication' => [
			'fhirType' => 'BackboneElement',
			'propertyKind' => 'backbone',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitItemAdjudication',
			'variants' => null,
		],
		'detail' => [
			'fhirType' => 'BackboneElement',
			'propertyKind' => 'backbone',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitItemDetail',
			'variants' => null,
		],
	];

	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive sequence Item instance identifier */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isRequired: true), \Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive $sequence = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive> careTeamSequence Applicable care team members */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
		public array $careTeamSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive> diagnosisSequence Applicable diagnoses */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
		public array $diagnosisSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive> procedureSequence Applicable procedures */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
		public array $procedureSequence = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive> informationSequence Applicable exception and supporting information */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
		public array $informationSequence = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept revenue Revenue or cost center code */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $revenue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept category Benefit classification */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept productOrService Billing, service, product, or drug code */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), \Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $productOrService = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> modifier Product or service billing modifiers */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'CodeableConcept',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
		)]
		public array $modifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> programCode Program the product or service is provided under */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'CodeableConcept',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
		)]
		public array $programCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period serviced Date or dates of service or product delivery */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'date',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive',
				'jsonKey' => 'servicedDate',
			],
			[
				'fhirType' => 'Period',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
				'jsonKey' => 'servicedPeriod',
			],
		],
		)]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|null $serviced = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Address|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference location Place of service or where product was supplied */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'CodeableConcept',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
				'jsonKey' => 'locationCodeableConcept',
			],
			[
				'fhirType' => 'Address',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Address',
				'jsonKey' => 'locationAddress',
			],
			[
				'fhirType' => 'Reference',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
				'jsonKey' => 'locationReference',
			],
		],
		)]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Address|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity quantity Count of products or services */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money unitPrice Fee, charge or cost per item */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Money', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $unitPrice = null,
		/** @var null|numeric-string factor Price scaling factor */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
		public ?string $factor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money net Total item cost */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Money', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Money $net = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> udi Unique device identifier */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'Reference',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
		)]
		public array $udi = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept bodySite Anatomical location */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $bodySite = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> subSite Anatomical sub-location */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'CodeableConcept',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
		)]
		public array $subSite = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> encounter Encounters related to this billed item */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'Reference',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
		)]
		public array $encounter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive> noteNumber Applicable note numbers */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
		public array $noteNumber = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitItemAdjudication> adjudication Adjudication details */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'BackboneElement',
			propertyKind: 'backbone',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitItemAdjudication',
		)]
		public array $adjudication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitItemDetail> detail Additional items */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'BackboneElement',
			propertyKind: 'backbone',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\ExplanationOfBenefit\ExplanationOfBenefitItemDetail',
		)]
		public array $detail = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
