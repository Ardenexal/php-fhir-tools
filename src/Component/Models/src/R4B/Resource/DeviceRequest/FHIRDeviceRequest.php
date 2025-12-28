<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DeviceRequest
 *
 * @description Represents a request for a patient to employ a medical device. The device may be an implantable device, or an external assistive device, such as a walker.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'DeviceRequest',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/DeviceRequest',
    fhirVersion: 'R4B',
)]
class FHIRDeviceRequest extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier External Request identifier */
        public array $identifier = [],
        /** @var array<FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<FHIRUri> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<FHIRReference> basedOn What request fulfills */
        public array $basedOn = [],
        /** @var array<FHIRReference> priorRequest What request replaces */
        public array $priorRequest = [],
        /** @var FHIRIdentifier|null groupIdentifier Identifier of composite request */
        public ?\FHIRIdentifier $groupIdentifier = null,
        /** @var FHIRRequestStatusType|null status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
        public ?\FHIRRequestStatusType $status = null,
        /** @var FHIRRequestIntentType|null intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?\FHIRRequestIntentType $intent = null,
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?\FHIRRequestPriorityType $priority = null,
        /** @var FHIRReference|FHIRCodeableConcept|null codeX Device requested */
        #[NotBlank]
        public \FHIRReference|\FHIRCodeableConcept|null $codeX = null,
        /** @var array<FHIRDeviceRequestParameter> parameter Device details */
        public array $parameter = [],
        /** @var FHIRReference|null subject Focus of request */
        #[NotBlank]
        public ?\FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter motivating request */
        public ?\FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRTiming|null occurrenceX Desired time or schedule for use */
        public \FHIRDateTime|\FHIRPeriod|\FHIRTiming|null $occurrenceX = null,
        /** @var FHIRDateTime|null authoredOn When recorded */
        public ?\FHIRDateTime $authoredOn = null,
        /** @var FHIRReference|null requester Who/what is requesting diagnostics */
        public ?\FHIRReference $requester = null,
        /** @var FHIRCodeableConcept|null performerType Filler role */
        public ?\FHIRCodeableConcept $performerType = null,
        /** @var FHIRReference|null performer Requested Filler */
        public ?\FHIRReference $performer = null,
        /** @var array<FHIRCodeableConcept> reasonCode Coded Reason for request */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Linked Reason for request */
        public array $reasonReference = [],
        /** @var array<FHIRReference> insurance Associated insurance coverage */
        public array $insurance = [],
        /** @var array<FHIRReference> supportingInfo Additional clinical information */
        public array $supportingInfo = [],
        /** @var array<FHIRAnnotation> note Notes or comments */
        public array $note = [],
        /** @var array<FHIRReference> relevantHistory Request provenance */
        public array $relevantHistory = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
