<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/ImplementationGuide
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
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri url Canonical identifier for this implementation guide, represented as a URI (globally unique) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRUri $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Additional identifier for the implementation guide (business identifier) */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string version Business version of the implementation guide */
		public FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding versionAlgorithmX How to compare versions */
		public FHIRString|string|FHIRCoding|null $versionAlgorithmX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string name Name for this implementation guide (computer friendly) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string title Name for this implementation guide (human friendly) */
		public FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRPublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean experimental For testing purposes, not real usage */
		public ?FHIRBoolean $experimental = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime date Date last changed */
		public ?FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string publisher Name of the publisher/steward (organization or individual) */
		public FHIRString|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description Natural language description of the implementation guide */
		public ?FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> jurisdiction Intended jurisdiction for implementation guide (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown purpose Why this implementation guide is defined */
		public ?FHIRMarkdown $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown copyright Use and/or publishing restrictions */
		public ?FHIRMarkdown $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string copyrightLabel Copyright holder and year(s) */
		public FHIRString|string|null $copyrightLabel = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId packageId NPM Package name for IG */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRId $packageId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSPDXLicenseType license SPDX license code for this IG (or not-open-source) */
		public ?FHIRSPDXLicenseType $license = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRFHIRVersionType> fhirVersion FHIR Version(s) this Implementation Guide targets */
		public array $fhirVersion = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRImplementationGuideDependsOn> dependsOn Another Implementation guide this depends on */
		public array $dependsOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRImplementationGuideGlobal> global Profiles that apply globally */
		public array $global = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRImplementationGuideDefinition definition Information needed to build the IG */
		public ?FHIRImplementationGuideDefinition $definition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRImplementationGuideManifest manifest Information about an assembled IG */
		public ?FHIRImplementationGuideManifest $manifest = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
