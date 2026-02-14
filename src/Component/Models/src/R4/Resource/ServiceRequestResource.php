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
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestIntentType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/ServiceRequest',
    fhirVersion: 'R4',
)]
class ServiceRequestResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Identifiers assigned to this order */
        public array $identifier = [],
        /** @var array<CanonicalPrimitive> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<UriPrimitive> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<Reference> basedOn What request fulfills */
        public array $basedOn = [],
        /** @var array<Reference> replaces What request replaces */
        public array $replaces = [],
        /** @var Identifier|null requisition Composite Request ID */
        public ?Identifier $requisition = null,
        /** @var RequestStatusType|null status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?RequestStatusType $status = null,
        /** @var RequestIntentType|null intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?RequestIntentType $intent = null,
        /** @var array<CodeableConcept> category Classification of service */
        public array $category = [],
        /** @var RequestPriorityType|null priority routine | urgent | asap | stat */
        public ?RequestPriorityType $priority = null,
        /** @var bool|null doNotPerform True if service/procedure should not be performed */
        public ?bool $doNotPerform = null,
        /** @var CodeableConcept|null code What is being requested/ordered */
        public ?CodeableConcept $code = null,
        /** @var array<CodeableConcept> orderDetail Additional order information */
        public array $orderDetail = [],
        /** @var Quantity|Ratio|Range|null quantityX Service amount */
        public Quantity|Ratio|Range|null $quantityX = null,
        /** @var Reference|null subject Individual or Entity the service is ordered for */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter in which the request was created */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|Period|Timing|null occurrenceX When service should occur */
        public DateTimePrimitive|Period|Timing|null $occurrenceX = null,
        /** @var bool|CodeableConcept|null asNeededX Preconditions for service */
        public bool|CodeableConcept|null $asNeededX = null,
        /** @var DateTimePrimitive|null authoredOn Date request signed */
        public ?DateTimePrimitive $authoredOn = null,
        /** @var Reference|null requester Who/what is requesting service */
        public ?Reference $requester = null,
        /** @var CodeableConcept|null performerType Performer role */
        public ?CodeableConcept $performerType = null,
        /** @var array<Reference> performer Requested performer */
        public array $performer = [],
        /** @var array<CodeableConcept> locationCode Requested location */
        public array $locationCode = [],
        /** @var array<Reference> locationReference Requested location */
        public array $locationReference = [],
        /** @var array<CodeableConcept> reasonCode Explanation/Justification for procedure or service */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Explanation/Justification for service or service */
        public array $reasonReference = [],
        /** @var array<Reference> insurance Associated insurance coverage */
        public array $insurance = [],
        /** @var array<Reference> supportingInfo Additional clinical information */
        public array $supportingInfo = [],
        /** @var array<Reference> specimen Procedure Samples */
        public array $specimen = [],
        /** @var array<CodeableConcept> bodySite Location on Body */
        public array $bodySite = [],
        /** @var array<Annotation> note Comments */
        public array $note = [],
        /** @var StringPrimitive|string|null patientInstruction Patient or consumer-oriented instructions */
        public StringPrimitive|string|null $patientInstruction = null,
        /** @var array<Reference> relevantHistory Request provenance */
        public array $relevantHistory = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
