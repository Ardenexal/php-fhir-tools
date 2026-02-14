<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/TriggerDefinition
 * @description A description of a triggering event. Triggering events can be named events, data events, or periodic, as determined by the type element.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'TriggerDefinition', fhirVersion: 'R4')]
class TriggerDefinition extends Element
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\TriggerTypeType type named-event | periodic | data-changed | data-added | data-modified | data-removed | data-accessed | data-access-ended */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?TriggerTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Name or URI that identifies the event */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive timingX Timing of the event */
		public Timing|Reference|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|null $timingX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement> data Triggering data of the event (multiple = 'and') */
		public array $data = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Expression condition Whether the event triggers (boolean expression) */
		public ?Expression $condition = null,
	) {
		parent::__construct($id, $extension);
	}
}
