<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
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
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Evidence
 *
 * @description The Evidence resource describes the conditional state (population and any exposures being compared within the population) and outcome (if specified) that the knowledge (evidence, assertion, recommendation) is about.
 */
#[FhirResource(type: 'Evidence', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Evidence', fhirVersion: 'R4')]
class EvidenceResource extends DomainResourceResource
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
        /** @var UriPrimitive|null url Canonical identifier for this evidence, represented as a URI (globally unique) */
        public ?UriPrimitive $url = null,
        /** @var array<Identifier> identifier Additional identifier for the evidence */
        public array $identifier = [],
        /** @var StringPrimitive|string|null version Business version of the evidence */
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this evidence (computer friendly) */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this evidence (human friendly) */
        public StringPrimitive|string|null $title = null,
        /** @var StringPrimitive|string|null shortTitle Title for use in informal contexts */
        public StringPrimitive|string|null $shortTitle = null,
        /** @var StringPrimitive|string|null subtitle Subordinate title of the Evidence */
        public StringPrimitive|string|null $subtitle = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?PublicationStatusType $status = null,
        /** @var DateTimePrimitive|null date Date last changed */
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher (organization or individual) */
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var MarkdownPrimitive|null description Natural language description of the evidence */
        public ?MarkdownPrimitive $description = null,
        /** @var array<Annotation> note Used for footnotes or explanatory notes */
        public array $note = [],
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for evidence (if applicable) */
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        public ?MarkdownPrimitive $copyright = null,
        /** @var DatePrimitive|null approvalDate When the evidence was approved by publisher */
        public ?DatePrimitive $approvalDate = null,
        /** @var DatePrimitive|null lastReviewDate When the evidence was last reviewed */
        public ?DatePrimitive $lastReviewDate = null,
        /** @var Period|null effectivePeriod When the evidence is expected to be used */
        public ?Period $effectivePeriod = null,
        /** @var array<CodeableConcept> topic The category of the Evidence, such as Education, Treatment, Assessment, etc. */
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
        /** @var Reference|null exposureBackground What population? */
        #[NotBlank]
        public ?Reference $exposureBackground = null,
        /** @var array<Reference> exposureVariant What exposure? */
        public array $exposureVariant = [],
        /** @var array<Reference> outcome What outcome? */
        public array $outcome = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
