<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Immunization;

/**
 * @description The protocol (set of recommendations) being followed by the provider who administered the dose.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement(parentResource: 'Immunization', elementPath: 'Immunization.protocolApplied', fhirVersion: 'R4B')]
class ImmunizationProtocolApplied extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement
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
		'series' => [
			'fhirType' => 'string',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'authority' => [
			'fhirType' => 'Reference',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => null,
			'variants' => null,
		],
		'targetDisease' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
			'variants' => null,
		],
		'doseNumber' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => true,
			'jsonKey' => null,
			'phpType' => null,
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
		'seriesDoses' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => true,
			'jsonKey' => null,
			'phpType' => null,
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
	];

	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension> extension Additional content defined by implementations */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string series Name of vaccine series */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $series = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference authority Who is responsible for publishing the recommendations */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference $authority = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept> targetDisease Vaccine preventatable disease being targetted */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'CodeableConcept',
			propertyKind: 'complex',
			isArray: true,
			phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
		)]
		public array $targetDisease = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string doseNumber Dose number within series */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isRequired: true,
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
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $doseNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string seriesDoses Recommended number of doses for immunity */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(
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
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive|string|null $seriesDoses = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
