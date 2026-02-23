<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\ImmunizationRecommendation;

/**
 * @description Vaccine administration recommendations.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'ImmunizationRecommendation',
	elementPath: 'ImmunizationRecommendation.recommendation',
	fhirVersion: 'R4B',
)]
class ImmunizationRecommendationRecommendation extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement
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
		'modifierExtension' => [
			'fhirType' => 'Extension',
			'propertyKind' => 'modifierExtension',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'vaccineCode' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'targetDisease' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'contraindicatedVaccineCode' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'forecastStatus' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'forecastReason' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'dateCriterion' => [
			'fhirType' => 'BackboneElement',
			'propertyKind' => 'backbone',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'description' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'series' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'doseNumberX' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => true,
			'jsonKey' => null,
			'variants' => [
				[
					'fhirType' => 'positiveInt',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive',
					'jsonKey' => 'doseNumberPositiveInt',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'string',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
					'jsonKey' => 'doseNumberString',
					'isBuiltin' => false,
				],
			],
		],
		'seriesDosesX' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => true,
			'jsonKey' => null,
			'variants' => [
				[
					'fhirType' => 'positiveInt',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive',
					'jsonKey' => 'seriesDosesPositiveInt',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'string',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
					'jsonKey' => 'seriesDosesString',
					'isBuiltin' => false,
				],
			],
		],
		'supportingImmunization' => [
			'fhirType' => 'Reference',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'supportingPatientInformation' => [
			'fhirType' => 'Reference',
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept> vaccineCode Vaccine  or vaccine group recommendation applies to */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isArray: true)]
		public array $vaccineCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept targetDisease Disease to be immunized against */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $targetDisease = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept> contraindicatedVaccineCode Vaccine which is contraindicated to fulfill the recommendation */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isArray: true)]
		public array $contraindicatedVaccineCode = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept forecastStatus Vaccine recommendation status */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), \Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $forecastStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept> forecastReason Vaccine administration status reason */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isArray: true)]
		public array $forecastReason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\ImmunizationRecommendation\ImmunizationRecommendationRecommendationDateCriterion> dateCriterion Dates governing proposed immunization */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isArray: true)]
		public array $dateCriterion = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string description Protocol details */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string series Name of vaccination series */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $series = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string doseNumberX Recommended dose number within series */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'positiveInt',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive',
				'jsonKey' => 'doseNumberPositiveInt',
			],
			[
				'fhirType' => 'string',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
				'jsonKey' => 'doseNumberString',
			],
		],
		)]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $doseNumberX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string seriesDosesX Recommended number of doses for immunity */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'positiveInt',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive',
				'jsonKey' => 'seriesDosesPositiveInt',
			],
			[
				'fhirType' => 'string',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
				'jsonKey' => 'seriesDosesString',
			],
		],
		)]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $seriesDosesX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference> supportingImmunization Past immunizations supporting recommendation */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isArray: true)]
		public array $supportingImmunization = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference> supportingPatientInformation Patient observations supporting recommendation */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isArray: true)]
		public array $supportingPatientInformation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
