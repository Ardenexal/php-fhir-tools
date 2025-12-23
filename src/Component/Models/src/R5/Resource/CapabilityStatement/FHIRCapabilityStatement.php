<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/CapabilityStatement
 * @description A Capability Statement documents a set of capabilities (behaviors) of a FHIR Server or Client for a particular version of FHIR that may be used as a statement of actual server functionality or a statement of required or desired server implementation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'CapabilityStatement',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/CapabilityStatement',
	fhirVersion: 'R5',
)]
class FHIRCapabilityStatement extends FHIRDomainResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri url Canonical identifier for this capability statement, represented as a URI (globally unique) */
		public ?FHIRUri $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Additional identifier for the CapabilityStatement (business identifier) */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string version Business version of the capability statement */
		public FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding versionAlgorithmX How to compare versions */
		public FHIRString|string|FHIRCoding|null $versionAlgorithmX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string name Name for this capability statement (computer friendly) */
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string title Name for this capability statement (human friendly) */
		public FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRPublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean experimental For testing purposes, not real usage */
		public ?FHIRBoolean $experimental = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime date Date last changed */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string publisher Name of the publisher/steward (organization or individual) */
		public FHIRString|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description Natural language description of the capability statement */
		public ?FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> jurisdiction Intended jurisdiction for capability statement (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown purpose Why this capability statement is defined */
		public ?FHIRMarkdown $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown copyright Use and/or publishing restrictions */
		public ?FHIRMarkdown $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string copyrightLabel Copyright holder and year(s) */
		public FHIRString|string|null $copyrightLabel = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementKindType kind instance | capability | requirements */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCapabilityStatementKindType $kind = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical> instantiates Canonical URL of another capability statement this implements */
		public array $instantiates = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical> imports Canonical URL of another capability statement this adds to */
		public array $imports = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementSoftware software Software that is covered by this capability statement */
		public ?FHIRCapabilityStatementSoftware $software = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementImplementation implementation If this describes a specific instance */
		public ?FHIRCapabilityStatementImplementation $implementation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRFHIRVersionType fhirVersion FHIR Version the system supports */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRFHIRVersionType $fhirVersion = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMimeTypesType> format formats supported (xml | json | ttl | mime type) */
		public array $format = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMimeTypesType> patchFormat Patch formats supported */
		public array $patchFormat = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType> acceptLanguage Languages supported */
		public array $acceptLanguage = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical> implementationGuide Implementation guides supported */
		public array $implementationGuide = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementRest> rest If the endpoint is a RESTful one */
		public array $rest = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementMessaging> messaging If messaging is supported */
		public array $messaging = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementDocument> document Document definition */
		public array $document = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
