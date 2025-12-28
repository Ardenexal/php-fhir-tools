<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/TriggerDefinition
 * @description A description of a triggering event. Triggering events can be named events, data events, or periodic, as determined by the type element.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'TriggerDefinition', fhirVersion: 'R5')]
class FHIRTriggerDefinition extends FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTriggerTypeType type named-event | periodic | data-changed | data-added | data-modified | data-removed | data-accessed | data-access-ended */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRTriggerTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string name Name or URI that identifies the event */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept code Coded definition of the event */
		public ?FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical subscriptionTopic What event */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $subscriptionTopic = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime timingX Timing of the event */
		public FHIRTiming|FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime|null $timingX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDataRequirement> data Triggering data of the event (multiple = 'and') */
		public array $data = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExpression condition Whether the event triggers (boolean expression) */
		public ?FHIRExpression $condition = null,
	) {
		parent::__construct($id, $extension);
	}
}
