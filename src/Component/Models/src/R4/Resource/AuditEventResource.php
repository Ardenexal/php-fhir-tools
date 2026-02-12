<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Security)
 * @see http://hl7.org/fhir/StructureDefinition/AuditEvent
 * @description A record of an event made for purposes of maintaining a security log. Typical uses include detection of intrusion attempts and monitoring for inappropriate usage.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'AuditEvent', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/AuditEvent', fhirVersion: 'R4')]
class AuditEventResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding type Type/identifier of event */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding> subtype More specific type/id for the event */
		public array $subtype = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AuditEventActionType action Type of action performed during the event */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AuditEventActionType $action = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period When the activity occurred */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive recorded Time when the event was recorded */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive $recorded = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AuditEventOutcomeType outcome Whether the event succeeded or failed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AuditEventOutcomeType $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string outcomeDesc Description of the event outcome */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $outcomeDesc = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> purposeOfEvent The purposeOfUse of the event */
		public array $purposeOfEvent = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEvent\AuditEventAgent> agent Actor involved in the event */
		public array $agent = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEvent\AuditEventSource source Audit Event Reporter */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?AuditEvent\AuditEventSource $source = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEvent\AuditEventEntity> entity Data or objects used */
		public array $entity = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
