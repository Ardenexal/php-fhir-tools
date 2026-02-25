<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Appointment;

/**
 * @description The details of the recurrence pattern or template that is used to generate recurring appointments.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Appointment', elementPath: 'Appointment.recurrenceTemplate', fhirVersion: 'R5')]
class AppointmentRecurrenceTemplate extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement
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
		'timezone' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'recurrenceType' => [
			'fhirType' => 'CodeableConcept',
			'propertyKind' => 'complex',
			'isArray' => false,
			'isRequired' => true,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'lastOccurrenceDate' => [
			'fhirType' => 'date',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'occurrenceCount' => [
			'fhirType' => 'positiveInt',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'occurrenceDate' => [
			'fhirType' => 'date',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'weeklyTemplate' => [
			'fhirType' => 'BackboneElement',
			'propertyKind' => 'backbone',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'monthlyTemplate' => [
			'fhirType' => 'BackboneElement',
			'propertyKind' => 'backbone',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'yearlyTemplate' => [
			'fhirType' => 'BackboneElement',
			'propertyKind' => 'backbone',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'excludingDate' => [
			'fhirType' => 'date',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'excludingRecurrenceId' => [
			'fhirType' => 'positiveInt',
			'propertyKind' => 'primitive',
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension> extension Additional content defined by implementations */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept timezone The timezone of the occurrences */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept $timezone = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept recurrenceType The frequency of the recurrence */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), \Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept $recurrenceType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive lastOccurrenceDate The date when the recurrence should end */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive $lastOccurrenceDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive occurrenceCount The number of planned occurrences */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive $occurrenceCount = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive> occurrenceDate Specific dates for a recurring set of appointments (no template) */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'date', propertyKind: 'primitive', isArray: true)]
		public array $occurrenceDate = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\Appointment\AppointmentRecurrenceTemplateWeeklyTemplate weeklyTemplate Information about weekly recurring appointments */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
		public ?AppointmentRecurrenceTemplateWeeklyTemplate $weeklyTemplate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\Appointment\AppointmentRecurrenceTemplateMonthlyTemplate monthlyTemplate Information about monthly recurring appointments */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
		public ?AppointmentRecurrenceTemplateMonthlyTemplate $monthlyTemplate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\Appointment\AppointmentRecurrenceTemplateYearlyTemplate yearlyTemplate Information about yearly recurring appointments */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
		public ?AppointmentRecurrenceTemplateYearlyTemplate $yearlyTemplate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive> excludingDate Any dates that should be excluded from the series */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'date', propertyKind: 'primitive', isArray: true)]
		public array $excludingDate = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive> excludingRecurrenceId Any recurrence IDs that should be excluded from the recurrence */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isArray: true)]
		public array $excludingRecurrenceId = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
