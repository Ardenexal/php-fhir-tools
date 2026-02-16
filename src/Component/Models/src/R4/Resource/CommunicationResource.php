<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\EventStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Communication\CommunicationPayload;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Communication
 *
 * @description An occurrence of information being transmitted; e.g. an alert that was sent to a responsible provider, a public health agency that was notified about a reportable condition.
 */
#[FhirResource(type: 'Communication', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Communication', fhirVersion: 'R4')]
class CommunicationResource extends DomainResourceResource
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
        /** @var array<CanonicalPrimitive> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<UriPrimitive> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<Reference> basedOn Request fulfilled by this communication */
        public array $basedOn = [],
        /** @var array<Reference> partOf Part of this action */
        public array $partOf = [],
        /** @var array<Reference> inResponseTo Reply to */
        public array $inResponseTo = [],
        /** @var EventStatusType|null status preparation | in-progress | not-done | on-hold | stopped | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?EventStatusType $status = null,
        /** @var CodeableConcept|null statusReason Reason for current status */
        public ?CodeableConcept $statusReason = null,
        /** @var array<CodeableConcept> category Message category */
        public array $category = [],
        /** @var RequestPriorityType|null priority routine | urgent | asap | stat */
        public ?RequestPriorityType $priority = null,
        /** @var array<CodeableConcept> medium A channel of communication */
        public array $medium = [],
        /** @var Reference|null subject Focus of message */
        public ?Reference $subject = null,
        /** @var CodeableConcept|null topic Description of the purpose/content */
        public ?CodeableConcept $topic = null,
        /** @var array<Reference> about Resources that pertain to this communication */
        public array $about = [],
        /** @var Reference|null encounter Encounter created as part of */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|null sent When sent */
        public ?DateTimePrimitive $sent = null,
        /** @var DateTimePrimitive|null received When received */
        public ?DateTimePrimitive $received = null,
        /** @var array<Reference> recipient Message recipient */
        public array $recipient = [],
        /** @var Reference|null sender Message sender */
        public ?Reference $sender = null,
        /** @var array<CodeableConcept> reasonCode Indication for message */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Why was communication done? */
        public array $reasonReference = [],
        /** @var array<CommunicationPayload> payload Message payload */
        public array $payload = [],
        /** @var array<Annotation> note Comments made about the communication */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
