<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Terminology Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ValueSet
 *
 * @description A ValueSet resource instance specifies a set of codes drawn from one or more code systems, intended for use in a particular context. Value sets link between [CodeSystem](codesystem.html) definitions and their use in [coded elements](terminologies.html).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'ValueSet', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/ValueSet', fhirVersion: 'R5')]
class FHIRValueSet extends FHIRDomainResource
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
        /** @var FHIRUri|null url Canonical identifier for this value set, represented as a URI (globally unique) */
        public ?\FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the value set (business identifier) */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the value set */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public \FHIRString|string|\FHIRCoding|null $versionAlgorithmX = null,
        /** @var FHIRString|string|null name Name for this value set (computer friendly) */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this value set (human friendly) */
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
        /** @var FHIRMarkdown|null description Natural language description of the value set */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for value set (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRBoolean|null immutable Indicates whether or not any change to the content logical definition may occur */
        public ?\FHIRBoolean $immutable = null,
        /** @var FHIRMarkdown|null purpose Why this value set is defined */
        public ?\FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?\FHIRMarkdown $copyright = null,
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) */
        public \FHIRString|string|null $copyrightLabel = null,
        /** @var FHIRDate|null approvalDate When the ValueSet was approved by publisher */
        public ?\FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate When the ValueSet was last reviewed by the publisher */
        public ?\FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod When the ValueSet is expected to be used */
        public ?\FHIRPeriod $effectivePeriod = null,
        /** @var array<FHIRCodeableConcept> topic E.g. Education, Treatment, Assessment, etc */
        public array $topic = [],
        /** @var array<FHIRContactDetail> author Who authored the ValueSet */
        public array $author = [],
        /** @var array<FHIRContactDetail> editor Who edited the ValueSet */
        public array $editor = [],
        /** @var array<FHIRContactDetail> reviewer Who reviewed the ValueSet */
        public array $reviewer = [],
        /** @var array<FHIRContactDetail> endorser Who endorsed the ValueSet */
        public array $endorser = [],
        /** @var array<FHIRRelatedArtifact> relatedArtifact Additional documentation, citations, etc */
        public array $relatedArtifact = [],
        /** @var FHIRValueSetCompose|null compose Content logical definition of the value set (CLD) */
        public ?\FHIRValueSetCompose $compose = null,
        /** @var FHIRValueSetExpansion|null expansion Used when the value set is "expanded" */
        public ?\FHIRValueSetExpansion $expansion = null,
        /** @var FHIRValueSetScope|null scope Description of the semantic space the Value Set Expansion is intended to cover and should further clarify the text in ValueSet.description */
        public ?\FHIRValueSetScope $scope = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
