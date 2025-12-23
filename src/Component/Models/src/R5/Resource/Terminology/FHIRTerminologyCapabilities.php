<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Terminology Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/TerminologyCapabilities
 * @description A TerminologyCapabilities resource documents a set of capabilities (behaviors) of a FHIR Terminology Server that may be used as a statement of actual server functionality or a statement of required or desired server implementation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'TerminologyCapabilities',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/TerminologyCapabilities',
	fhirVersion: 'R5',
)]
class FHIRTerminologyCapabilities extends FHIRDomainResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri url Canonical identifier for this terminology capabilities, represented as a URI (globally unique) */
		public ?FHIRUri $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Additional identifier for the terminology capabilities */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string version Business version of the terminology capabilities */
		public FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding versionAlgorithmX How to compare versions */
		public FHIRString|string|FHIRCoding|null $versionAlgorithmX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string name Name for this terminology capabilities (computer friendly) */
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string title Name for this terminology capabilities (human friendly) */
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description Natural language description of the terminology capabilities */
		public ?FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> jurisdiction Intended jurisdiction for terminology capabilities (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown purpose Why this terminology capabilities is defined */
		public ?FHIRMarkdown $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown copyright Use and/or publishing restrictions */
		public ?FHIRMarkdown $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string copyrightLabel Copyright holder and year(s) */
		public FHIRString|string|null $copyrightLabel = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementKindType kind instance | capability | requirements */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCapabilityStatementKindType $kind = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTerminologyCapabilitiesSoftware software Software that is covered by this terminology capability statement */
		public ?FHIRTerminologyCapabilitiesSoftware $software = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTerminologyCapabilitiesImplementation implementation If this describes a specific instance */
		public ?FHIRTerminologyCapabilitiesImplementation $implementation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean lockedDate Whether lockedDate is supported */
		public ?FHIRBoolean $lockedDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTerminologyCapabilitiesCodeSystem> codeSystem A code system supported by the server */
		public array $codeSystem = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTerminologyCapabilitiesExpansion expansion Information about the [ValueSet/$expand](valueset-operation-expand.html) operation */
		public ?FHIRTerminologyCapabilitiesExpansion $expansion = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeSearchSupportType codeSearch in-compose | in-expansion | in-compose-or-expansion */
		public ?FHIRCodeSearchSupportType $codeSearch = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTerminologyCapabilitiesValidateCode validateCode Information about the [ValueSet/$validate-code](valueset-operation-validate-code.html) operation */
		public ?FHIRTerminologyCapabilitiesValidateCode $validateCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTerminologyCapabilitiesTranslation translation Information about the [ConceptMap/$translate](conceptmap-operation-translate.html) operation */
		public ?FHIRTerminologyCapabilitiesTranslation $translation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTerminologyCapabilitiesClosure closure Information about the [ConceptMap/$closure](conceptmap-operation-closure.html) operation */
		public ?FHIRTerminologyCapabilitiesClosure $closure = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
