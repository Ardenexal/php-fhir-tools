<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @description A set of rules that describe when the event is scheduled.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Timing.repeat', fhirVersion: 'R5')]
class TimingRepeat extends Element
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
		'bounds' => [
			'fhirType' => 'choice',
			'propertyKind' => 'choice',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => true,
			'jsonKey' => null,
			'variants' => [
				[
					'fhirType' => 'Duration',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration',
					'jsonKey' => 'boundsDuration',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Range',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Range',
					'jsonKey' => 'boundsRange',
					'isBuiltin' => false,
				],
				[
					'fhirType' => 'Period',
					'propertyKind' => 'complex',
					'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Period',
					'jsonKey' => 'boundsPeriod',
					'isBuiltin' => false,
				],
			],
		],
		'count' => [
			'fhirType' => 'positiveInt',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'countMax' => [
			'fhirType' => 'positiveInt',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'duration' => [
			'fhirType' => 'decimal',
			'propertyKind' => 'scalar',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'durationMax' => [
			'fhirType' => 'decimal',
			'propertyKind' => 'scalar',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'durationUnit' => [
			'fhirType' => 'code',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'frequency' => [
			'fhirType' => 'positiveInt',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'frequencyMax' => [
			'fhirType' => 'positiveInt',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'period' => [
			'fhirType' => 'decimal',
			'propertyKind' => 'scalar',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'periodMax' => [
			'fhirType' => 'decimal',
			'propertyKind' => 'scalar',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'periodUnit' => [
			'fhirType' => 'code',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'dayOfWeek' => [
			'fhirType' => 'code',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'timeOfDay' => [
			'fhirType' => 'time',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'when' => [
			'fhirType' => 'code',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'offset' => [
			'fhirType' => 'unsignedInt',
			'propertyKind' => 'primitive',
			'isArray' => false,
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension> extension Additional content defined by implementations */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Range|\Ardenexal\FHIRTools\Component\Models\R5\DataType\Period bounds Length/Range of lengths, or (Start and/or end) limits */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(
			fhirType: 'choice',
			propertyKind: 'choice',
			isChoice: true,
			variants: [
			[
				'fhirType' => 'Duration',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration',
				'jsonKey' => 'boundsDuration',
			],
			[
				'fhirType' => 'Range',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Range',
				'jsonKey' => 'boundsRange',
			],
			[
				'fhirType' => 'Period',
				'propertyKind' => 'complex',
				'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Period',
				'jsonKey' => 'boundsPeriod',
			],
		],
		)]
		public Duration|Range|Period|null $bounds = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive count Number of times to repeat */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive $count = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive countMax Maximum number of times to repeat */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive $countMax = null,
		/** @var null|float duration How long when it happens */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
		public ?float $duration = null,
		/** @var null|float durationMax How long when it happens (Max) */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
		public ?float $durationMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\UnitsOfTimeType durationUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public ?UnitsOfTimeType $durationUnit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive frequency Indicates the number of repetitions that should occur within a period. I.e. Event occurs frequency times per period */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive $frequency = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive frequencyMax Event occurs up to frequencyMax times per period */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive $frequencyMax = null,
		/** @var null|float period The duration to which the frequency applies. I.e. Event occurs frequency times per period */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
		public ?float $period = null,
		/** @var null|float periodMax Upper limit of period (3-4 hours) */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
		public ?float $periodMax = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\UnitsOfTimeType periodUnit s | min | h | d | wk | mo | a - unit of time (UCUM) */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
		public ?UnitsOfTimeType $periodUnit = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\DaysOfWeekType> dayOfWeek mon | tue | wed | thu | fri | sat | sun */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
		public array $dayOfWeek = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\TimePrimitive> timeOfDay Time of day for action */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'time', propertyKind: 'primitive', isArray: true)]
		public array $timeOfDay = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\EventTimingType> when Code for time period of occurrence */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
		public array $when = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive offset Minutes from event (before or after) */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive $offset = null,
	) {
		parent::__construct($id, $extension);
	}
}
