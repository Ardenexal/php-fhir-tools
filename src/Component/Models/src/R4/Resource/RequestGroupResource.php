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
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestIntentType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\RequestGroup\RequestGroupAction;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/RequestGroup
 *
 * @description A group of related requests that can be used to capture intended activities that have inter-dependencies such as "give this medication after that one".
 */
#[FhirResource(type: 'RequestGroup', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/RequestGroup', fhirVersion: 'R4')]
class RequestGroupResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business identifier */
        public array $identifier = [],
        /** @var array<CanonicalPrimitive> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<UriPrimitive> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<Reference> basedOn Fulfills plan, proposal, or order */
        public array $basedOn = [],
        /** @var array<Reference> replaces Request(s) replaced by this request */
        public array $replaces = [],
        /** @var Identifier|null groupIdentifier Composite request this is part of */
        public ?Identifier $groupIdentifier = null,
        /** @var RequestStatusType|null status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?RequestStatusType $status = null,
        /** @var RequestIntentType|null intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?RequestIntentType $intent = null,
        /** @var RequestPriorityType|null priority routine | urgent | asap | stat */
        public ?RequestPriorityType $priority = null,
        /** @var CodeableConcept|null code What's being requested/ordered */
        public ?CodeableConcept $code = null,
        /** @var Reference|null subject Who the request group is about */
        public ?Reference $subject = null,
        /** @var Reference|null encounter Created as part of */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|null authoredOn When the request group was authored */
        public ?DateTimePrimitive $authoredOn = null,
        /** @var Reference|null author Device or practitioner that authored the request group */
        public ?Reference $author = null,
        /** @var array<CodeableConcept> reasonCode Why the request group is needed */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Why the request group is needed */
        public array $reasonReference = [],
        /** @var array<Annotation> note Additional notes about the response */
        public array $note = [],
        /** @var array<RequestGroupAction> action Proposed actions, if any */
        public array $action = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
