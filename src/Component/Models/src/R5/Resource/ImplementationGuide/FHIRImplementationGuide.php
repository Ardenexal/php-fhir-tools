<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ImplementationGuide
 *
 * @description A set of rules of how a particular interoperability or standards problem is solved - typically through the use of FHIR resources. This resource is used to gather all the parts of an implementation guide into a logical whole and to publish a computable definition of all the parts.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'ImplementationGuide',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ImplementationGuide',
    fhirVersion: 'R5',
)]
class FHIRImplementationGuide extends FHIRDomainResource
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
        /** @var FHIRUri|null url Canonical identifier for this implementation guide, represented as a URI (globally unique) */
        #[NotBlank]
        public ?\FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the implementation guide (business identifier) */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the implementation guide */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public \FHIRString|string|\FHIRCoding|null $versionAlgorithmX = null,
        /** @var FHIRString|string|null name Name for this implementation guide (computer friendly) */
        #[NotBlank]
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this implementation guide (human friendly) */
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
        /** @var FHIRMarkdown|null description Natural language description of the implementation guide */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for implementation guide (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this implementation guide is defined */
        public ?\FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?\FHIRMarkdown $copyright = null,
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) */
        public \FHIRString|string|null $copyrightLabel = null,
        /** @var FHIRId|null packageId NPM Package name for IG */
        #[NotBlank]
        public ?\FHIRId $packageId = null,
        /** @var FHIRSPDXLicenseType|null license SPDX license code for this IG (or not-open-source) */
        public ?\FHIRSPDXLicenseType $license = null,
        /** @var array<FHIRFHIRVersionType> fhirVersion FHIR Version(s) this Implementation Guide targets */
        public array $fhirVersion = [],
        /** @var array<FHIRImplementationGuideDependsOn> dependsOn Another Implementation guide this depends on */
        public array $dependsOn = [],
        /** @var array<FHIRImplementationGuideGlobal> global Profiles that apply globally */
        public array $global = [],
        /** @var FHIRImplementationGuideDefinition|null definition Information needed to build the IG */
        public ?\FHIRImplementationGuideDefinition $definition = null,
        /** @var FHIRImplementationGuideManifest|null manifest Information about an assembled IG */
        public ?\FHIRImplementationGuideManifest $manifest = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
