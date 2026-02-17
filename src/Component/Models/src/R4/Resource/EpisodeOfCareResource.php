<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/EpisodeOfCare
 * @description An association between a patient and an organization / healthcare provider(s) during which time encounters may occur. The managing organization assumes a level of responsibility for the patient during this time.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'EpisodeOfCare', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/EpisodeOfCare', fhirVersion: 'R4')]
class EpisodeOfCareResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business Identifier(s) relevant for this EpisodeOfCare */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\EpisodeOfCareStatusType status planned | waitlist | active | onhold | finished | cancelled | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\EpisodeOfCareStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\EpisodeOfCare\EpisodeOfCareStatusHistory> statusHistory Past list of status codes (the current status may be included to cover the start date of the status) */
		public array $statusHistory = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> type Type/class  - e.g. specialist referral, disease management */
		public array $type = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\EpisodeOfCare\EpisodeOfCareDiagnosis> diagnosis The list of diagnosis relevant to this episode of care */
		public array $diagnosis = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference patient The patient who is the focus of this episode of care */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference managingOrganization Organization that assumes care */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $managingOrganization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period Interval during responsibility is assumed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> referralRequest Originating Referral Request(s) */
		public array $referralRequest = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference careManager Care manager/care coordinator for the patient */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $careManager = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> team Other practitioners facilitating this episode of care */
		public array $team = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> account The set of accounts that may be used for billing for this EpisodeOfCare */
		public array $account = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
