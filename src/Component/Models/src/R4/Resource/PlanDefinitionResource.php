<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PlanDefinition\PlanDefinitionAction;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PlanDefinition\PlanDefinitionGoal;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/PlanDefinition
 *
 * @description This resource allows for the definition of various types of plans as a sharable, consumable, and executable artifact. The resource is general enough to support the description of a broad range of clinical artifacts such as clinical decision support rules, order sets and protocols.
 */
#[FhirResource(
    type: 'PlanDefinition',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/PlanDefinition',
    fhirVersion: 'R4',
)]
class PlanDefinitionResource extends DomainResourceResource
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
        /** @var UriPrimitive|null url Canonical identifier for this plan definition, represented as a URI (globally unique) */
        public ?UriPrimitive $url = null,
        /** @var array<Identifier> identifier Additional identifier for the plan definition */
        public array $identifier = [],
        /** @var StringPrimitive|string|null version Business version of the plan definition */
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this plan definition (computer friendly) */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this plan definition (human friendly) */
        public StringPrimitive|string|null $title = null,
        /** @var StringPrimitive|string|null subtitle Subordinate title of the plan definition */
        public StringPrimitive|string|null $subtitle = null,
        /** @var CodeableConcept|null type order-set | clinical-protocol | eca-rule | workflow-definition */
        public ?CodeableConcept $type = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?PublicationStatusType $status = null,
        /** @var bool|null experimental For testing purposes, not real usage */
        public ?bool $experimental = null,
        /** @var CodeableConcept|Reference|null subjectX Type of individual the plan definition is focused on */
        public CodeableConcept|Reference|null $subjectX = null,
        /** @var DateTimePrimitive|null date Date last changed */
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher (organization or individual) */
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var MarkdownPrimitive|null description Natural language description of the plan definition */
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for plan definition (if applicable) */
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this plan definition is defined */
        public ?MarkdownPrimitive $purpose = null,
        /** @var StringPrimitive|string|null usage Describes the clinical usage of the plan */
        public StringPrimitive|string|null $usage = null,
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        public ?MarkdownPrimitive $copyright = null,
        /** @var DatePrimitive|null approvalDate When the plan definition was approved by publisher */
        public ?DatePrimitive $approvalDate = null,
        /** @var DatePrimitive|null lastReviewDate When the plan definition was last reviewed */
        public ?DatePrimitive $lastReviewDate = null,
        /** @var Period|null effectivePeriod When the plan definition is expected to be used */
        public ?Period $effectivePeriod = null,
        /** @var array<CodeableConcept> topic E.g. Education, Treatment, Assessment */
        public array $topic = [],
        /** @var array<ContactDetail> author Who authored the content */
        public array $author = [],
        /** @var array<ContactDetail> editor Who edited the content */
        public array $editor = [],
        /** @var array<ContactDetail> reviewer Who reviewed the content */
        public array $reviewer = [],
        /** @var array<ContactDetail> endorser Who endorsed the content */
        public array $endorser = [],
        /** @var array<RelatedArtifact> relatedArtifact Additional documentation, citations */
        public array $relatedArtifact = [],
        /** @var array<CanonicalPrimitive> library Logic used by the plan definition */
        public array $library = [],
        /** @var array<PlanDefinitionGoal> goal What the plan is trying to accomplish */
        public array $goal = [],
        /** @var array<PlanDefinitionAction> action Action defined by the plan */
        public array $action = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
