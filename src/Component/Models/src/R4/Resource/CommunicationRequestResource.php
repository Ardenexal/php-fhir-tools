<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CommunicationRequest\CommunicationRequestPayload;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/CommunicationRequest
 *
 * @description A request to convey information; e.g. the CDS system proposes that an alert be sent to a responsible provider, the CDS system proposes that the public health agency be notified about a reportable condition.
 */
#[FhirResource(
    type: 'CommunicationRequest',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/CommunicationRequest',
    fhirVersion: 'R4',
)]
class CommunicationRequestResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Unique identifier */
        public array $identifier = [],
        /** @var array<Reference> basedOn Fulfills plan or proposal */
        public array $basedOn = [],
        /** @var array<Reference> replaces Request(s) replaced by this request */
        public array $replaces = [],
        /** @var Identifier|null groupIdentifier Composite request this is part of */
        public ?Identifier $groupIdentifier = null,
        /** @var RequestStatusType|null status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?RequestStatusType $status = null,
        /** @var CodeableConcept|null statusReason Reason for current status */
        public ?CodeableConcept $statusReason = null,
        /** @var array<CodeableConcept> category Message category */
        public array $category = [],
        /** @var RequestPriorityType|null priority routine | urgent | asap | stat */
        public ?RequestPriorityType $priority = null,
        /** @var bool|null doNotPerform True if request is prohibiting action */
        public ?bool $doNotPerform = null,
        /** @var array<CodeableConcept> medium A channel of communication */
        public array $medium = [],
        /** @var Reference|null subject Focus of message */
        public ?Reference $subject = null,
        /** @var array<Reference> about Resources that pertain to this communication request */
        public array $about = [],
        /** @var Reference|null encounter Encounter created as part of */
        public ?Reference $encounter = null,
        /** @var array<CommunicationRequestPayload> payload Message payload */
        public array $payload = [],
        /** @var DateTimePrimitive|Period|null occurrenceX When scheduled */
        public DateTimePrimitive|Period|null $occurrenceX = null,
        /** @var DateTimePrimitive|null authoredOn When request transitioned to being actionable */
        public ?DateTimePrimitive $authoredOn = null,
        /** @var Reference|null requester Who/what is requesting service */
        public ?Reference $requester = null,
        /** @var array<Reference> recipient Message recipient */
        public array $recipient = [],
        /** @var Reference|null sender Message sender */
        public ?Reference $sender = null,
        /** @var array<CodeableConcept> reasonCode Why is communication needed? */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Why is communication needed? */
        public array $reasonReference = [],
        /** @var array<Annotation> note Comments made about communication request */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
