<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRequestIntentType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRequestStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ServiceRequest
 *
 * @description A record of a request for service such as diagnostic investigations, treatments, or operations to be performed.
 */
#[FhirResource(
    type: 'ServiceRequest',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ServiceRequest',
    fhirVersion: 'R4B',
)]
class FHIRServiceRequest extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
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
        public ?FHIRIdentifier $requisition = null,
        /** @var FHIRRequestStatusType|null status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?FHIRRequestStatusType $status = null,
        /** @var FHIRRequestIntentType|null intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?FHIRRequestIntentType $intent = null,
        /** @var array<FHIRCodeableConcept> category Classification of service */
        public array $category = [],
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?FHIRRequestPriorityType $priority = null,
        /** @var FHIRBoolean|null doNotPerform True if service/procedure should not be performed */
        public ?FHIRBoolean $doNotPerform = null,
        /** @var FHIRCodeableConcept|null code What is being requested/ordered */
        public ?FHIRCodeableConcept $code = null,
        /** @var array<FHIRCodeableConcept> orderDetail Additional order information */
        public array $orderDetail = [],
        /** @var FHIRQuantity|FHIRRatio|FHIRRange|null quantityX Service amount */
        public FHIRQuantity|FHIRRatio|FHIRRange|null $quantityX = null,
        /** @var FHIRReference|null subject Individual or Entity the service is ordered for */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter in which the request was created */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRTiming|null occurrenceX When service should occur */
        public FHIRDateTime|FHIRPeriod|FHIRTiming|null $occurrenceX = null,
        /** @var FHIRBoolean|FHIRCodeableConcept|null asNeededX Preconditions for service */
        public FHIRBoolean|FHIRCodeableConcept|null $asNeededX = null,
        /** @var FHIRDateTime|null authoredOn Date request signed */
        public ?FHIRDateTime $authoredOn = null,
        /** @var FHIRReference|null requester Who/what is requesting service */
        public ?FHIRReference $requester = null,
        /** @var FHIRCodeableConcept|null performerType Performer role */
        public ?FHIRCodeableConcept $performerType = null,
        /** @var array<FHIRReference> performer Requested performer */
        public array $performer = [],
        /** @var array<FHIRCodeableConcept> locationCode Requested location */
        public array $locationCode = [],
        /** @var array<FHIRReference> locationReference Requested location */
        public array $locationReference = [],
        /** @var array<FHIRCodeableConcept> reasonCode Explanation/Justification for procedure or service */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Explanation/Justification for service or service */
        public array $reasonReference = [],
        /** @var array<FHIRReference> insurance Associated insurance coverage */
        public array $insurance = [],
        /** @var array<FHIRReference> supportingInfo Additional clinical information */
        public array $supportingInfo = [],
        /** @var array<FHIRReference> specimen Procedure Samples */
        public array $specimen = [],
        /** @var array<FHIRCodeableConcept> bodySite Location on Body */
        public array $bodySite = [],
        /** @var array<FHIRAnnotation> note Comments */
        public array $note = [],
        /** @var FHIRString|string|null patientInstruction Patient or consumer-oriented instructions */
        public FHIRString|string|null $patientInstruction = null,
        /** @var array<FHIRReference> relevantHistory Request provenance */
        public array $relevantHistory = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
