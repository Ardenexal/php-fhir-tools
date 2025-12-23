<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Security)
 * @see http://hl7.org/fhir/StructureDefinition/AuditEvent
 * @description A record of an event relevant for purposes such as operations, privacy, security, maintenance, and performance analysis.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'AuditEvent', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/AuditEvent', fhirVersion: 'R5')]
class FHIRAuditEvent extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> category Type/identifier of event */
		public array $category = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code Specific type of event */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAuditEventActionType action Type of action performed during the event */
		public ?FHIRAuditEventActionType $action = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAuditEventSeverityType severity emergency | alert | critical | error | warning | notice | informational | debug */
		public ?FHIRAuditEventSeverityType $severity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime occurredX When the activity occurred */
		public FHIRPeriod|FHIRDateTime|null $occurredX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInstant recorded Time when the event was recorded */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRInstant $recorded = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAuditEventOutcome outcome Whether the event succeeded or failed */
		public ?FHIRAuditEventOutcome $outcome = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> authorization Authorization related to the event */
		public array $authorization = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> basedOn Workflow authorization within which this event occurred */
		public array $basedOn = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference patient The patient is the subject of the data used/created/updated/deleted during the activity */
		public ?FHIRReference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference encounter Encounter within which this event occurred or which the event is tightly associated */
		public ?FHIRReference $encounter = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAuditEventAgent> agent Actor involved in the event */
		public array $agent = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAuditEventSource source Audit Event Reporter */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRAuditEventSource $source = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAuditEventEntity> entity Data or objects used */
		public array $entity = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
