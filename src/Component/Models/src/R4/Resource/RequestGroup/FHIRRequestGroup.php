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
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRequestIntentType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRequestPriorityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRequestStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/RequestGroup
 *
 * @description A group of related requests that can be used to capture intended activities that have inter-dependencies such as "give this medication after that one".
 */
#[FhirResource(type: 'RequestGroup', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/RequestGroup', fhirVersion: 'R4')]
class FHIRRequestGroup extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business identifier */
        public array $identifier = [],
        /** @var array<FHIRCanonical> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<FHIRUri> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<FHIRReference> basedOn Fulfills plan, proposal, or order */
        public array $basedOn = [],
        /** @var array<FHIRReference> replaces Request(s) replaced by this request */
        public array $replaces = [],
        /** @var FHIRIdentifier|null groupIdentifier Composite request this is part of */
        public ?FHIRIdentifier $groupIdentifier = null,
        /** @var FHIRRequestStatusType|null status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?FHIRRequestStatusType $status = null,
        /** @var FHIRRequestIntentType|null intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
        #[NotBlank]
        public ?FHIRRequestIntentType $intent = null,
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?FHIRRequestPriorityType $priority = null,
        /** @var FHIRCodeableConcept|null code What's being requested/ordered */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRReference|null subject Who the request group is about */
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Created as part of */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|null authoredOn When the request group was authored */
        public ?FHIRDateTime $authoredOn = null,
        /** @var FHIRReference|null author Device or practitioner that authored the request group */
        public ?FHIRReference $author = null,
        /** @var array<FHIRCodeableConcept> reasonCode Why the request group is needed */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Why the request group is needed */
        public array $reasonReference = [],
        /** @var array<FHIRAnnotation> note Additional notes about the response */
        public array $note = [],
        /** @var array<FHIRRequestGroupAction> action Proposed actions, if any */
        public array $action = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
