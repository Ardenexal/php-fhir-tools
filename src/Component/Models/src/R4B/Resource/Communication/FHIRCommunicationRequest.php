<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/CommunicationRequest
 *
 * @description A request to convey information; e.g. the CDS system proposes that an alert be sent to a responsible provider, the CDS system proposes that the public health agency be notified about a reportable condition.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'CommunicationRequest',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/CommunicationRequest',
    fhirVersion: 'R4B',
)]
class FHIRCommunicationRequest extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Unique identifier */
        public array $identifier = [],
        /** @var array<FHIRReference> basedOn Fulfills plan or proposal */
        public array $basedOn = [],
        /** @var array<FHIRReference> replaces Request(s) replaced by this request */
        public array $replaces = [],
        /** @var FHIRIdentifier|null groupIdentifier Composite request this is part of */
        public ?\FHIRIdentifier $groupIdentifier = null,
        /** @var FHIRRequestStatusType|null status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?\FHIRRequestStatusType $status = null,
        /** @var FHIRCodeableConcept|null statusReason Reason for current status */
        public ?\FHIRCodeableConcept $statusReason = null,
        /** @var array<FHIRCodeableConcept> category Message category */
        public array $category = [],
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?\FHIRRequestPriorityType $priority = null,
        /** @var FHIRBoolean|null doNotPerform True if request is prohibiting action */
        public ?\FHIRBoolean $doNotPerform = null,
        /** @var array<FHIRCodeableConcept> medium A channel of communication */
        public array $medium = [],
        /** @var FHIRReference|null subject Focus of message */
        public ?\FHIRReference $subject = null,
        /** @var array<FHIRReference> about Resources that pertain to this communication request */
        public array $about = [],
        /** @var FHIRReference|null encounter Encounter created as part of */
        public ?\FHIRReference $encounter = null,
        /** @var array<FHIRCommunicationRequestPayload> payload Message payload */
        public array $payload = [],
        /** @var FHIRDateTime|FHIRPeriod|null occurrenceX When scheduled */
        public \FHIRDateTime|\FHIRPeriod|null $occurrenceX = null,
        /** @var FHIRDateTime|null authoredOn When request transitioned to being actionable */
        public ?\FHIRDateTime $authoredOn = null,
        /** @var FHIRReference|null requester Who/what is requesting service */
        public ?\FHIRReference $requester = null,
        /** @var array<FHIRReference> recipient Message recipient */
        public array $recipient = [],
        /** @var FHIRReference|null sender Message sender */
        public ?\FHIRReference $sender = null,
        /** @var array<FHIRCodeableConcept> reasonCode Why is communication needed? */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Why is communication needed? */
        public array $reasonReference = [],
        /** @var array<FHIRAnnotation> note Comments made about communication request */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
