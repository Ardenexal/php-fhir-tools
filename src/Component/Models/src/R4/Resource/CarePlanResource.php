<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CarePlanIntentType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RequestStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CarePlan\CarePlanActivity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/CarePlan
 *
 * @description Describes the intention of how one or more practitioners intend to deliver care for a particular patient, group or community for a period of time, possibly limited to care for a specific condition or set of conditions.
 */
#[FhirResource(type: 'CarePlan', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/CarePlan', fhirVersion: 'R4')]
class CarePlanResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External Ids for this plan */
        public array $identifier = [],
        /** @var array<CanonicalPrimitive> instantiatesCanonical Instantiates FHIR protocol or definition */
        public array $instantiatesCanonical = [],
        /** @var array<UriPrimitive> instantiatesUri Instantiates external protocol or definition */
        public array $instantiatesUri = [],
        /** @var array<Reference> basedOn Fulfills CarePlan */
        public array $basedOn = [],
        /** @var array<Reference> replaces CarePlan replaced by this CarePlan */
        public array $replaces = [],
        /** @var array<Reference> partOf Part of referenced CarePlan */
        public array $partOf = [],
        /** @var RequestStatusType|null status draft | active | on-hold | revoked | completed | entered-in-error | unknown */
        #[NotBlank]
        public ?RequestStatusType $status = null,
        /** @var CarePlanIntentType|null intent proposal | plan | order | option */
        #[NotBlank]
        public ?CarePlanIntentType $intent = null,
        /** @var array<CodeableConcept> category Type of plan */
        public array $category = [],
        /** @var StringPrimitive|string|null title Human-friendly name for the care plan */
        public StringPrimitive|string|null $title = null,
        /** @var StringPrimitive|string|null description Summary of nature of plan */
        public StringPrimitive|string|null $description = null,
        /** @var Reference|null subject Who the care plan is for */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter created as part of */
        public ?Reference $encounter = null,
        /** @var Period|null period Time period plan covers */
        public ?Period $period = null,
        /** @var DateTimePrimitive|null created Date record was first recorded */
        public ?DateTimePrimitive $created = null,
        /** @var Reference|null author Who is the designated responsible party */
        public ?Reference $author = null,
        /** @var array<Reference> contributor Who provided the content of the care plan */
        public array $contributor = [],
        /** @var array<Reference> careTeam Who's involved in plan? */
        public array $careTeam = [],
        /** @var array<Reference> addresses Health issues this plan addresses */
        public array $addresses = [],
        /** @var array<Reference> supportingInfo Information considered as part of plan */
        public array $supportingInfo = [],
        /** @var array<Reference> goal Desired outcome of plan */
        public array $goal = [],
        /** @var array<CarePlanActivity> activity Action to occur as part of plan */
        public array $activity = [],
        /** @var array<Annotation> note Comments about the plan */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
