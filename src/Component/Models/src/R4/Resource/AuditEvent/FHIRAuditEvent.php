<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Security)
 * @see http://hl7.org/fhir/StructureDefinition/AuditEvent
 * @description A record of an event made for purposes of maintaining a security log. Typical uses include detection of intrusion attempts and monitoring for inappropriate usage.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'AuditEvent', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/AuditEvent', fhirVersion: 'R4')]
class FHIRAuditEvent extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding type Type/identifier of event */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCoding $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding> subtype More specific type/id for the event */
		public array $subtype = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAuditEventActionType action Type of action performed during the event */
		public ?FHIRAuditEventActionType $action = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod period When the activity occurred */
		public ?FHIRPeriod $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInstant recorded Time when the event was recorded */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRInstant $recorded = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAuditEventOutcomeType outcome Whether the event succeeded or failed */
		public ?FHIRAuditEventOutcomeType $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string outcomeDesc Description of the event outcome */
		public FHIRString|string|null $outcomeDesc = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> purposeOfEvent The purposeOfUse of the event */
		public array $purposeOfEvent = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAuditEventAgent> agent Actor involved in the event */
		public array $agent = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAuditEventSource source Audit Event Reporter */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRAuditEventSource $source = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAuditEventEntity> entity Data or objects used */
		public array $entity = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
