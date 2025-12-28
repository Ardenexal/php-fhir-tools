<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DeviceRequest
 *
 * @description Represents a request for a patient to employ a medical device. The device may be an implantable device, or an external assistive device, such as a walker.
 */
#[FhirResource(type: 'DeviceRequest', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/DeviceRequest', fhirVersion: 'R5')]
class FHIRDeviceRequest extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
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
        /** @var array<FHIRReference> replaces What request replaces */
        public array $replaces = [],
        /** @var FHIRIdentifier|null groupIdentifier Identifier of composite request */
        public ?FHIRIdentifier $groupIdentifier = null,
        /** @var FHIRRequestStatusType|null status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
        public ?FHIRRequestStatusType $status = null,
        /** @var FHIRRequestIntentType|null intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?FHIRRequestIntentType $intent = null,
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?FHIRRequestPriorityType $priority = null,
        /** @var FHIRBoolean|null doNotPerform True if the request is to stop or not to start using the device */
        public ?FHIRBoolean $doNotPerform = null,
        /** @var FHIRCodeableReference|null code Device requested */
        #[NotBlank]
        public ?FHIRCodeableReference $code = null,
        /** @var FHIRInteger|null quantity Quantity of devices to supply */
        public ?FHIRInteger $quantity = null,
        /** @var array<FHIRDeviceRequestParameter> parameter Device details */
        public array $parameter = [],
        /** @var FHIRReference|null subject Focus of request */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter motivating request */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|FHIRTiming|null occurrenceX Desired time or schedule for use */
        public FHIRDateTime|FHIRPeriod|FHIRTiming|null $occurrenceX = null,
        /** @var FHIRDateTime|null authoredOn When recorded */
        public ?FHIRDateTime $authoredOn = null,
        /** @var FHIRReference|null requester Who/what submitted the device request */
        public ?FHIRReference $requester = null,
        /** @var FHIRCodeableReference|null performer Requested Filler */
        public ?FHIRCodeableReference $performer = null,
        /** @var array<FHIRCodeableReference> reason Coded/Linked Reason for request */
        public array $reason = [],
        /** @var FHIRBoolean|null asNeeded PRN status of request */
        public ?FHIRBoolean $asNeeded = null,
        /** @var FHIRCodeableConcept|null asNeededFor Device usage reason */
        public ?FHIRCodeableConcept $asNeededFor = null,
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
