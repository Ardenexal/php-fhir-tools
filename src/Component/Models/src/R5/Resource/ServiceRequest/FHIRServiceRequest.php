<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ServiceRequest
 *
 * @description A record of a request for service such as diagnostic investigations, treatments, or operations to be performed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'ServiceRequest',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ServiceRequest',
    fhirVersion: 'R5',
)]
class FHIRServiceRequest extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Identifiers assigned to this order */
        public array $identifier = [],
        /** @var array<FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<FHIRUri> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<FHIRReference> basedOn What request fulfills */
        public array $basedOn = [],
        /** @var array<FHIRReference> replaces What request replaces */
        public array $replaces = [],
        /** @var FHIRIdentifier|null requisition Composite Request ID */
        public ?\FHIRIdentifier $requisition = null,
        /** @var FHIRRequestStatusType|null status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?\FHIRRequestStatusType $status = null,
        /** @var FHIRRequestIntentType|null intent proposal | plan | directive | order + */
        #[NotBlank]
        public ?\FHIRRequestIntentType $intent = null,
        /** @var array<FHIRCodeableConcept> category Classification of service */
        public array $category = [],
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?\FHIRRequestPriorityType $priority = null,
        /** @var FHIRBoolean|null doNotPerform True if service/procedure should not be performed */
        public ?\FHIRBoolean $doNotPerform = null,
        /** @var FHIRCodeableReference|null code What is being requested/ordered */
        public ?\FHIRCodeableReference $code = null,
        /** @var array<FHIRServiceRequestOrderDetail> orderDetail Additional order information */
        public array $orderDetail = [],
        /** @var FHIRQuantity|FHIRRatio|FHIRRange|null quantityX Service amount */
        public \FHIRQuantity|\FHIRRatio|\FHIRRange|null $quantityX = null,
        /** @var FHIRReference|null subject Individual or Entity the service is ordered for */
        #[NotBlank]
        public ?\FHIRReference $subject = null,
        /** @var array<FHIRReference> focus What the service request is about, when it is not about the subject of record */
        public array $focus = [],
        /** @var FHIRReference|null encounter Encounter in which the request was created */
        public ?\FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRTiming|null occurrenceX When service should occur */
        public \FHIRDateTime|\FHIRPeriod|\FHIRTiming|null $occurrenceX = null,
        /** @var FHIRBoolean|FHIRCodeableConcept|null asNeededX Preconditions for service */
        public \FHIRBoolean|\FHIRCodeableConcept|null $asNeededX = null,
        /** @var FHIRDateTime|null authoredOn Date request signed */
        public ?\FHIRDateTime $authoredOn = null,
        /** @var FHIRReference|null requester Who/what is requesting service */
        public ?\FHIRReference $requester = null,
        /** @var FHIRCodeableConcept|null performerType Performer role */
        public ?\FHIRCodeableConcept $performerType = null,
        /** @var array<FHIRReference> performer Requested performer */
        public array $performer = [],
        /** @var array<FHIRCodeableReference> location Requested location */
        public array $location = [],
        /** @var array<FHIRCodeableReference> reason Explanation/Justification for procedure or service */
        public array $reason = [],
        /** @var array<FHIRReference> insurance Associated insurance coverage */
        public array $insurance = [],
        /** @var array<FHIRCodeableReference> supportingInfo Additional clinical information */
        public array $supportingInfo = [],
        /** @var array<FHIRReference> specimen Procedure Samples */
        public array $specimen = [],
        /** @var array<FHIRCodeableConcept> bodySite Coded location on Body */
        public array $bodySite = [],
        /** @var FHIRReference|null bodyStructure BodyStructure-based location on the body */
        public ?\FHIRReference $bodyStructure = null,
        /** @var array<FHIRAnnotation> note Comments */
        public array $note = [],
        /** @var array<FHIRServiceRequestPatientInstruction> patientInstruction Patient or consumer-oriented instructions */
        public array $patientInstruction = [],
        /** @var array<FHIRReference> relevantHistory Request provenance */
        public array $relevantHistory = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
