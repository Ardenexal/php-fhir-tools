<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Security)
 * @see http://hl7.org/fhir/StructureDefinition/Provenance
 * @description Provenance of a resource is a record that describes entities and processes involved in producing and delivering or otherwise influencing that resource. Provenance provides a critical foundation for assessing authenticity, enabling trust, and allowing reproducibility. Provenance assertions are a form of contextual metadata and can themselves become important records with their own provenance. Provenance statement indicates clinical significance in terms of confidence in authenticity, reliability, and trustworthiness, integrity, and stage in lifecycle (e.g. Document Completion - has the artifact been legally authenticated), all of which may impact security, privacy, and trust policies.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Provenance', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Provenance', fhirVersion: 'R5')]
class FHIRProvenance extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> target Target Reference(s) (usually version specific) */
		public array $target = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime occurredX When the activity occurred */
		public FHIRPeriod|FHIRDateTime|null $occurredX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInstant recorded When the activity was recorded / updated */
		public ?FHIRInstant $recorded = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri> policy Policy or plan the activity was defined by */
		public array $policy = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference location Where the activity occurred, if relevant */
		public ?FHIRReference $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> authorization Authorization (purposeOfUse) related to the event */
		public array $authorization = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept activity Activity that occurred */
		public ?FHIRCodeableConcept $activity = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> basedOn Workflow authorization within which this event occurred */
		public array $basedOn = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference patient The patient is the subject of the data created/updated (.target) by the activity */
		public ?FHIRReference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference encounter Encounter within which this event occurred or which the event is tightly associated */
		public ?FHIRReference $encounter = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRProvenanceAgent> agent Actor involved */
		public array $agent = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRProvenanceEntity> entity An entity used in this activity */
		public array $entity = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSignature> signature Signature on target */
		public array $signature = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
