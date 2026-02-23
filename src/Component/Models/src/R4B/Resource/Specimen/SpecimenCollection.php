<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Specimen;

/**
 * @description Details concerning the specimen collection.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Specimen', elementPath: 'Specimen.collection', fhirVersion: 'R4B')]
class SpecimenCollection extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement
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
		'collector' => [
			'fhirType' => 'Reference',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'collectedX' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => true,
			'jsonKey' => null,
			'variants' => [
				[
					'fhirType' => 'dateTime',
					'propertyKind' => 'primitive',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive',
					'jsonKey' => 'collectedDateTime',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Period',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period',
					'jsonKey' => 'collectedPeriod',
					'isBuiltin' => false,
				],
			],
		],
		'duration' => [
			'fhirType' => 'Duration',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'quantity' => [
			'fhirType' => 'Quantity',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'method' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'bodySite' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'fastingStatusX' => [
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
					'jsonKey' => 'fastingStatusCodeableConcept',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Duration',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration',
					'jsonKey' => 'fastingStatusDuration',
					'isBuiltin' => false,
				],
			],
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference collector Who collected the specimen */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference $collector = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period collectedX Collection time */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'dateTime',
				'propertyKind' => 'primitive',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive',
				'jsonKey' => 'collectedDateTime',
			],
			[
				'fhirType' => 'Period',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period',
				'jsonKey' => 'collectedPeriod',
			],
		],
		)]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Period|null $collectedX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration duration How long it took to collect specimen */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration $duration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity quantity The quantity of specimen collected */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept method Technique used to perform collection */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept bodySite Anatomical collection site */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $bodySite = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration fastingStatusX Whether or how long patient abstained from food and/or drink */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'CodeableConcept',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
				'jsonKey' => 'fastingStatusCodeableConcept',
			],
			[
				'fhirType' => 'Duration',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration',
				'jsonKey' => 'fastingStatusDuration',
			],
		],
		)]
		public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration|null $fastingStatusX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
