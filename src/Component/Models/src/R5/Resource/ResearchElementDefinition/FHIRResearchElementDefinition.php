<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRResearchElementTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRVariableTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
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
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ResearchElementDefinition',
    fhirVersion: 'R5',
)]
class FHIRResearchElementDefinition extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Canonical identifier for this research element definition, represented as a URI (globally unique) */
        public ?FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the research element definition */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the research element definition */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|null name Name for this research element definition (computer friendly) */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this research element definition (human friendly) */
        public FHIRString|string|null $title = null,
        /** @var FHIRString|string|null shortTitle Title for use in informal contexts */
        public FHIRString|string|null $shortTitle = null,
        /** @var FHIRString|string|null subtitle Subordinate title of the ResearchElementDefinition */
        public FHIRString|string|null $subtitle = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?FHIRBoolean $experimental = null,
        /** @var FHIRCodeableConcept|FHIRReference|null subjectX E.g. Patient, Practitioner, RelatedPerson, Organization, Location, Device */
        public FHIRCodeableConcept|FHIRReference|null $subjectX = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher (organization or individual) */
        public FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the research element definition */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRString|string> comment Used for footnotes or explanatory notes */
        public array $comment = [],
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for research element definition (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this research element definition is defined */
        public ?FHIRMarkdown $purpose = null,
        /** @var FHIRString|string|null usage Describes the clinical usage of the ResearchElementDefinition */
        public FHIRString|string|null $usage = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?FHIRMarkdown $copyright = null,
        /** @var FHIRDate|null approvalDate When the research element definition was approved by publisher */
        public ?FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate When the research element definition was last reviewed */
        public ?FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod When the research element definition is expected to be used */
        public ?FHIRPeriod $effectivePeriod = null,
        /** @var array<FHIRCodeableConcept> topic The category of the ResearchElementDefinition, such as Education, Treatment, Assessment, etc. */
        public array $topic = [],
        /** @var array<FHIRContactDetail> author Who authored the content */
        public array $author = [],
        /** @var array<FHIRContactDetail> editor Who edited the content */
        public array $editor = [],
        /** @var array<FHIRContactDetail> reviewer Who reviewed the content */
        public array $reviewer = [],
        /** @var array<FHIRContactDetail> endorser Who endorsed the content */
        public array $endorser = [],
        /** @var array<FHIRRelatedArtifact> relatedArtifact Additional documentation, citations, etc. */
        public array $relatedArtifact = [],
        /** @var array<FHIRCanonical> library Logic used by the ResearchElementDefinition */
        public array $library = [],
        /** @var FHIRResearchElementTypeType|null type population | exposure | outcome */
        #[NotBlank]
        public ?FHIRResearchElementTypeType $type = null,
        /** @var FHIRVariableTypeType|null variableType dichotomous | continuous | descriptive */
        public ?FHIRVariableTypeType $variableType = null,
        /** @var array<FHIRResearchElementDefinitionCharacteristic> characteristic What defines the members of the research element */
        public array $characteristic = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
