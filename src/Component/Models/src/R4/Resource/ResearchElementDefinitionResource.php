<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\EvidenceVariableTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ResearchElementTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ResearchElementDefinition\ResearchElementDefinitionCharacteristic;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ResearchElementDefinition
 *
 * @description The ResearchElementDefinition resource describes a "PICO" element that knowledge (evidence, assertion, recommendation) is about.
 */
#[FhirResource(
    type: 'ResearchElementDefinition',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/ResearchElementDefinition',
    fhirVersion: 'R4',
)]
class ResearchElementDefinitionResource extends DomainResourceResource
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
        /** @var UriPrimitive|null url Canonical identifier for this research element definition, represented as a URI (globally unique) */
        public ?UriPrimitive $url = null,
        /** @var array<Identifier> identifier Additional identifier for the research element definition */
        public array $identifier = [],
        /** @var StringPrimitive|string|null version Business version of the research element definition */
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this research element definition (computer friendly) */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this research element definition (human friendly) */
        public StringPrimitive|string|null $title = null,
        /** @var StringPrimitive|string|null shortTitle Title for use in informal contexts */
        public StringPrimitive|string|null $shortTitle = null,
        /** @var StringPrimitive|string|null subtitle Subordinate title of the ResearchElementDefinition */
        public StringPrimitive|string|null $subtitle = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?PublicationStatusType $status = null,
        /** @var bool|null experimental For testing purposes, not real usage */
        public ?bool $experimental = null,
        /** @var CodeableConcept|Reference|null subjectX E.g. Patient, Practitioner, RelatedPerson, Organization, Location, Device */
        public CodeableConcept|Reference|null $subjectX = null,
        /** @var DateTimePrimitive|null date Date last changed */
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher (organization or individual) */
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var MarkdownPrimitive|null description Natural language description of the research element definition */
        public ?MarkdownPrimitive $description = null,
        /** @var array<StringPrimitive|string> comment Used for footnotes or explanatory notes */
        public array $comment = [],
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for research element definition (if applicable) */
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this research element definition is defined */
        public ?MarkdownPrimitive $purpose = null,
        /** @var StringPrimitive|string|null usage Describes the clinical usage of the ResearchElementDefinition */
        public StringPrimitive|string|null $usage = null,
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        public ?MarkdownPrimitive $copyright = null,
        /** @var DatePrimitive|null approvalDate When the research element definition was approved by publisher */
        public ?DatePrimitive $approvalDate = null,
        /** @var DatePrimitive|null lastReviewDate When the research element definition was last reviewed */
        public ?DatePrimitive $lastReviewDate = null,
        /** @var Period|null effectivePeriod When the research element definition is expected to be used */
        public ?Period $effectivePeriod = null,
        /** @var array<CodeableConcept> topic The category of the ResearchElementDefinition, such as Education, Treatment, Assessment, etc. */
        public array $topic = [],
        /** @var array<ContactDetail> author Who authored the content */
        public array $author = [],
        /** @var array<ContactDetail> editor Who edited the content */
        public array $editor = [],
        /** @var array<ContactDetail> reviewer Who reviewed the content */
        public array $reviewer = [],
        /** @var array<ContactDetail> endorser Who endorsed the content */
        public array $endorser = [],
        /** @var array<RelatedArtifact> relatedArtifact Additional documentation, citations, etc. */
        public array $relatedArtifact = [],
        /** @var array<CanonicalPrimitive> library Logic used by the ResearchElementDefinition */
        public array $library = [],
        /** @var ResearchElementTypeType|null type population | exposure | outcome */
        #[NotBlank]
        public ?ResearchElementTypeType $type = null,
        /** @var EvidenceVariableTypeType|null variableType dichotomous | continuous | descriptive */
        public ?EvidenceVariableTypeType $variableType = null,
        /** @var array<ResearchElementDefinitionCharacteristic> characteristic What defines the members of the research element */
        public array $characteristic = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
