<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/TriggerDefinition
 * @description A description of a triggering event. Triggering events can be named events, data events, or periodic, as determined by the type element.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'TriggerDefinition', fhirVersion: 'R4B')]
class FHIRTriggerDefinition extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTriggerTypeType type named-event | periodic | data-changed | data-added | data-modified | data-removed | data-accessed | data-access-ended */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTriggerTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string name Name or URI that identifies the event */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTiming|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime timingX Timing of the event */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTiming|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime|null $timingX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDataRequirement> data Triggering data of the event (multiple = 'and') */
		public array $data = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExpression condition Whether the event triggers (boolean expression) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExpression $condition = null,
	) {
		parent::__construct($id, $extension);
	}
}
