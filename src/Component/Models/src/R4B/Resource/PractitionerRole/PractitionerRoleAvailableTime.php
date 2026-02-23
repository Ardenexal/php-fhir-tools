<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\PractitionerRole;

/**
 * @description A collection of times the practitioner is available or performing this role at the location and/or healthcareservice.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PractitionerRole', elementPath: 'PractitionerRole.availableTime', fhirVersion: 'R4B')]
class PractitionerRoleAvailableTime extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement
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
		'daysOfWeek' => [
			'fhirType' => 'code',
			'propertyKind' => 'primitive',
			'isArray' => true,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'allDay' => [
			'fhirType' => 'boolean',
			'propertyKind' => 'scalar',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'availableStartTime' => [
			'fhirType' => 'time',
			'propertyKind' => 'primitive',
			'isArray' => false,
			'isRequired' => false,
			'isChoice' => false,
			'jsonKey' => null,
			'variants' => null,
		],
		'availableEndTime' => [
			'fhirType' => 'time',
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension> extension Additional content defined by implementations */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\DaysOfWeekType> daysOfWeek mon | tue | wed | thu | fri | sat | sun */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
		public array $daysOfWeek = [],
		/** @var null|bool allDay Always available? e.g. 24 hour service */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
		public ?bool $allDay = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive availableStartTime Opening time of day (ignored if allDay = true) */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'time', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive $availableStartTime = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive availableEndTime Closing time of day (ignored if allDay = true) */
		#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty(fhirType: 'time', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\TimePrimitive $availableEndTime = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
