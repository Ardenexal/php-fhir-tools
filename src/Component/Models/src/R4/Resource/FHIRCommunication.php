<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Communication
 *
 * @description An occurrence of information being transmitted; e.g. an alert that was sent to a responsible provider, a public health agency that was notified about a reportable condition.
 */
#[FhirResource(type: 'Communication', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Communication', fhirVersion: 'R4')]
class FHIRCommunication extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Unique identifier */
        public array $identifier = [],
        /** @var array<FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<FHIRUri> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<FHIRReference> basedOn Request fulfilled by this communication */
        public array $basedOn = [],
        /** @var array<FHIRReference> partOf Part of this action */
        public array $partOf = [],
        /** @var array<FHIRReference> inResponseTo Reply to */
        public array $inResponseTo = [],
        /** @var FHIREventStatusType|null status preparation | in-progress | not-done | on-hold | stopped | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?FHIREventStatusType $status = null,
        /** @var FHIRCodeableConcept|null statusReason Reason for current status */
        public ?FHIRCodeableConcept $statusReason = null,
        /** @var array<FHIRCodeableConcept> category Message category */
        public array $category = [],
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?FHIRRequestPriorityType $priority = null,
        /** @var array<FHIRCodeableConcept> medium A channel of communication */
        public array $medium = [],
        /** @var FHIRReference|null subject Focus of message */
        public ?FHIRReference $subject = null,
        /** @var FHIRCodeableConcept|null topic Description of the purpose/content */
        public ?FHIRCodeableConcept $topic = null,
        /** @var array<FHIRReference> about Resources that pertain to this communication */
        public array $about = [],
        /** @var FHIRReference|null encounter Encounter created as part of */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|null sent When sent */
        public ?FHIRDateTime $sent = null,
        /** @var FHIRDateTime|null received When received */
        public ?FHIRDateTime $received = null,
        /** @var array<FHIRReference> recipient Message recipient */
        public array $recipient = [],
        /** @var FHIRReference|null sender Message sender */
        public ?FHIRReference $sender = null,
        /** @var array<FHIRCodeableConcept> reasonCode Indication for message */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Why was communication done? */
        public array $reasonReference = [],
        /** @var array<FHIRCommunicationPayload> payload Message payload */
        public array $payload = [],
        /** @var array<FHIRAnnotation> note Comments made about the communication */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
