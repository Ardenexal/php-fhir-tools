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
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestIntentType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceRequest\DeviceRequestParameter;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DeviceRequest
 *
 * @description Represents a request for a patient to employ a medical device. The device may be an implantable device, or an external assistive device, such as a walker.
 */
#[FhirResource(type: 'DeviceRequest', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/DeviceRequest', fhirVersion: 'R4')]
class DeviceRequestResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External Request identifier */
        public array $identifier = [],
        /** @var array<CanonicalPrimitive> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<UriPrimitive> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<Reference> basedOn What request fulfills */
        public array $basedOn = [],
        /** @var array<Reference> priorRequest What request replaces */
        public array $priorRequest = [],
        /** @var Identifier|null groupIdentifier Identifier of composite request */
        public ?Identifier $groupIdentifier = null,
        /** @var RequestStatusType|null status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
        public ?RequestStatusType $status = null,
        /** @var RequestIntentType|null intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?RequestIntentType $intent = null,
        /** @var RequestPriorityType|null priority routine | urgent | asap | stat */
        public ?RequestPriorityType $priority = null,
        /** @var Reference|CodeableConcept|null codeX Device requested */
        #[NotBlank]
        public Reference|CodeableConcept|null $codeX = null,
        /** @var array<DeviceRequestParameter> parameter Device details */
        public array $parameter = [],
        /** @var Reference|null subject Focus of request */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter motivating request */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|Period|Timing|null occurrenceX Desired time or schedule for use */
        public DateTimePrimitive|Period|Timing|null $occurrenceX = null,
        /** @var DateTimePrimitive|null authoredOn When recorded */
        public ?DateTimePrimitive $authoredOn = null,
        /** @var Reference|null requester Who/what is requesting diagnostics */
        public ?Reference $requester = null,
        /** @var CodeableConcept|null performerType Filler role */
        public ?CodeableConcept $performerType = null,
        /** @var Reference|null performer Requested Filler */
        public ?Reference $performer = null,
        /** @var array<CodeableConcept> reasonCode Coded Reason for request */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Linked Reason for request */
        public array $reasonReference = [],
        /** @var array<Reference> insurance Associated insurance coverage */
        public array $insurance = [],
        /** @var array<Reference> supportingInfo Additional clinical information */
        public array $supportingInfo = [],
        /** @var array<Annotation> note Notes or comments */
        public array $note = [],
        /** @var array<Reference> relevantHistory Request provenance */
        public array $relevantHistory = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
