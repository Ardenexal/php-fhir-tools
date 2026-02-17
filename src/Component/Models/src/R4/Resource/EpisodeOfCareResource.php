<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\EpisodeOfCareStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\EpisodeOfCare\EpisodeOfCareDiagnosis;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\EpisodeOfCare\EpisodeOfCareStatusHistory;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/EpisodeOfCare
 *
 * @description An association between a patient and an organization / healthcare provider(s) during which time encounters may occur. The managing organization assumes a level of responsibility for the patient during this time.
 */
#[FhirResource(type: 'EpisodeOfCare', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/EpisodeOfCare', fhirVersion: 'R4')]
class EpisodeOfCareResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Business Identifier(s) relevant for this EpisodeOfCare */
        public array $identifier = [],
        /** @var EpisodeOfCareStatusType|null status planned | waitlist | active | onhold | finished | cancelled | entered-in-error */
        #[NotBlank]
        public ?EpisodeOfCareStatusType $status = null,
        /** @var array<EpisodeOfCareStatusHistory> statusHistory Past list of status codes (the current status may be included to cover the start date of the status) */
        public array $statusHistory = [],
        /** @var array<CodeableConcept> type Type/class  - e.g. specialist referral, disease management */
        public array $type = [],
        /** @var array<EpisodeOfCareDiagnosis> diagnosis The list of diagnosis relevant to this episode of care */
        public array $diagnosis = [],
        /** @var Reference|null patient The patient who is the focus of this episode of care */
        #[NotBlank]
        public ?Reference $patient = null,
        /** @var Reference|null managingOrganization Organization that assumes care */
        public ?Reference $managingOrganization = null,
        /** @var Period|null period Interval during responsibility is assumed */
        public ?Period $period = null,
        /** @var array<Reference> referralRequest Originating Referral Request(s) */
        public array $referralRequest = [],
        /** @var Reference|null careManager Care manager/care coordinator for the patient */
        public ?Reference $careManager = null,
        /** @var array<Reference> team Other practitioners facilitating this episode of care */
        public array $team = [],
        /** @var array<Reference> account The set of accounts that may be used for billing for this EpisodeOfCare */
        public array $account = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
