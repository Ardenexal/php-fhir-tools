<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Terminology Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/CodeSystem
 *
 * @description The CodeSystem resource is used to declare the existence of and describe a code system or code system supplement and its key properties, and optionally define a part or all of its content.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'CodeSystem', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/CodeSystem', fhirVersion: 'R5')]
class FHIRCodeSystem extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Canonical identifier for this code system, represented as a URI (globally unique) (Coding.system) */
        public ?\FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the code system (business identifier) */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the code system (Coding.version) */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public \FHIRString|string|\FHIRCoding|null $versionAlgorithmX = null,
        /** @var FHIRString|string|null name Name for this code system (computer friendly) */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this code system (human friendly) */
        public \FHIRString|string|null $title = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?\FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?\FHIRBoolean $experimental = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher/steward (organization or individual) */
        public \FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the code system */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for code system (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this code system is defined */
        public ?\FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?\FHIRMarkdown $copyright = null,
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) */
        public \FHIRString|string|null $copyrightLabel = null,
        /** @var FHIRDate|null approvalDate When the CodeSystem was approved by publisher */
        public ?\FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate When the CodeSystem was last reviewed by the publisher */
        public ?\FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod When the CodeSystem is expected to be used */
        public ?\FHIRPeriod $effectivePeriod = null,
        /** @var array<FHIRCodeableConcept> topic E.g. Education, Treatment, Assessment, etc */
        public array $topic = [],
        /** @var array<FHIRContactDetail> author Who authored the CodeSystem */
        public array $author = [],
        /** @var array<FHIRContactDetail> editor Who edited the CodeSystem */
        public array $editor = [],
        /** @var array<FHIRContactDetail> reviewer Who reviewed the CodeSystem */
        public array $reviewer = [],
        /** @var array<FHIRContactDetail> endorser Who endorsed the CodeSystem */
        public array $endorser = [],
        /** @var array<FHIRRelatedArtifact> relatedArtifact Additional documentation, citations, etc */
        public array $relatedArtifact = [],
        /** @var FHIRBoolean|null caseSensitive If code comparison is case sensitive */
        public ?\FHIRBoolean $caseSensitive = null,
        /** @var FHIRCanonical|null valueSet Canonical reference to the value set with entire code system */
        public ?\FHIRCanonical $valueSet = null,
        /** @var FHIRCodeSystemHierarchyMeaningType|null hierarchyMeaning grouped-by | is-a | part-of | classified-with */
        public ?\FHIRCodeSystemHierarchyMeaningType $hierarchyMeaning = null,
        /** @var FHIRBoolean|null compositional If code system defines a compositional grammar */
        public ?\FHIRBoolean $compositional = null,
        /** @var FHIRBoolean|null versionNeeded If definitions are not stable */
        public ?\FHIRBoolean $versionNeeded = null,
        /** @var FHIRCodeSystemContentModeType|null content not-present | example | fragment | complete | supplement */
        #[NotBlank]
        public ?\FHIRCodeSystemContentModeType $content = null,
        /** @var FHIRCanonical|null supplements Canonical URL of Code System this adds designations and properties to */
        public ?\FHIRCanonical $supplements = null,
        /** @var FHIRUnsignedInt|null count Total concepts in the code system */
        public ?\FHIRUnsignedInt $count = null,
        /** @var array<FHIRCodeSystemFilter> filter Filter that can be used in a value set */
        public array $filter = [],
        /** @var array<FHIRCodeSystemProperty> property Additional information supplied about each concept */
        public array $property = [],
        /** @var array<FHIRCodeSystemConcept> concept Concepts in the code system */
        public array $concept = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
