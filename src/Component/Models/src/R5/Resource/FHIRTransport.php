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
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Transport
 *
 * @description Record of transport of item.
 */
#[FhirResource(type: 'Transport', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Transport', fhirVersion: 'R5')]
class FHIRTransport extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier External identifier */
        public array $identifier = [],
        /** @var FHIRCanonical|null instantiatesCanonical Formal definition of transport */
        public ?FHIRCanonical $instantiatesCanonical = null,
        /** @var FHIRUri|null instantiatesUri Formal definition of transport */
        public ?FHIRUri $instantiatesUri = null,
        /** @var array<FHIRReference> basedOn Request fulfilled by this transport */
        public array $basedOn = [],
        /** @var FHIRIdentifier|null groupIdentifier Requisition or grouper id */
        public ?FHIRIdentifier $groupIdentifier = null,
        /** @var array<FHIRReference> partOf Part of referenced event */
        public array $partOf = [],
        /** @var FHIRTransportStatusType|null status in-progress | completed | abandoned | cancelled | planned | entered-in-error */
        public ?FHIRTransportStatusType $status = null,
        /** @var FHIRCodeableConcept|null statusReason Reason for current status */
        public ?FHIRCodeableConcept $statusReason = null,
        /** @var FHIRTransportIntentType|null intent unknown | proposal | plan | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?FHIRTransportIntentType $intent = null,
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?FHIRRequestPriorityType $priority = null,
        /** @var FHIRCodeableConcept|null code Transport Type */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRString|string|null description Human-readable explanation of transport */
        public FHIRString|string|null $description = null,
        /** @var FHIRReference|null focus What transport is acting on */
        public ?FHIRReference $focus = null,
        /** @var FHIRReference|null for Beneficiary of the Transport */
        public ?FHIRReference $for = null,
        /** @var FHIRReference|null encounter Healthcare event during which this transport originated */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|null completionTime Completion time of the event (the occurrence) */
        public ?FHIRDateTime $completionTime = null,
        /** @var FHIRDateTime|null authoredOn Transport Creation Date */
        public ?FHIRDateTime $authoredOn = null,
        /** @var FHIRDateTime|null lastModified Transport Last Modified Date */
        public ?FHIRDateTime $lastModified = null,
        /** @var FHIRReference|null requester Who is asking for transport to be done */
        public ?FHIRReference $requester = null,
        /** @var array<FHIRCodeableConcept> performerType Requested performer */
        public array $performerType = [],
        /** @var FHIRReference|null owner Responsible individual */
        public ?FHIRReference $owner = null,
        /** @var FHIRReference|null location Where transport occurs */
        public ?FHIRReference $location = null,
        /** @var array<FHIRReference> insurance Associated insurance coverage */
        public array $insurance = [],
        /** @var array<FHIRAnnotation> note Comments made about the transport */
        public array $note = [],
        /** @var array<FHIRReference> relevantHistory Key events in history of the Transport */
        public array $relevantHistory = [],
        /** @var FHIRTransportRestriction|null restriction Constraints on fulfillment transports */
        public ?FHIRTransportRestriction $restriction = null,
        /** @var array<FHIRTransportInput> input Information used to perform transport */
        public array $input = [],
        /** @var array<FHIRTransportOutput> output Information produced as part of transport */
        public array $output = [],
        /** @var FHIRReference|null requestedLocation The desired location */
        #[NotBlank]
        public ?FHIRReference $requestedLocation = null,
        /** @var FHIRReference|null currentLocation The entity current location */
        #[NotBlank]
        public ?FHIRReference $currentLocation = null,
        /** @var FHIRCodeableReference|null reason Why transport is needed */
        public ?FHIRCodeableReference $reason = null,
        /** @var FHIRReference|null history Parent (or preceding) transport */
        public ?FHIRReference $history = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
