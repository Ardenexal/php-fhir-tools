<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Vocabulary)
 * @see http://hl7.org/fhir/StructureDefinition/TerminologyCapabilities
 * @description A TerminologyCapabilities resource documents a set of capabilities (behaviors) of a FHIR Terminology Server that may be used as a statement of actual server functionality or a statement of required or desired server implementation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'TerminologyCapabilities',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/TerminologyCapabilities',
	fhirVersion: 'R4',
)]
class TerminologyCapabilitiesResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive url Canonical identifier for this terminology capabilities, represented as a URI (globally unique) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string version Business version of the terminology capabilities */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Name for this terminology capabilities (computer friendly) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string title Name for this terminology capabilities (human friendly) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType $status = null,
		/** @var null|bool experimental For testing purposes, not real usage */
		public ?bool $experimental = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive date Date last changed */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string publisher Name of the publisher (organization or individual) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive description Natural language description of the terminology capabilities */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> jurisdiction Intended jurisdiction for terminology capabilities (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive purpose Why this terminology capabilities is defined */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive copyright Use and/or publishing restrictions */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CapabilityStatementKindType kind instance | capability | requirements */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CapabilityStatementKindType $kind = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesSoftware software Software that is covered by this terminology capability statement */
		public ?TerminologyCapabilities\TerminologyCapabilitiesSoftware $software = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesImplementation implementation If this describes a specific instance */
		public ?TerminologyCapabilities\TerminologyCapabilitiesImplementation $implementation = null,
		/** @var null|bool lockedDate Whether lockedDate is supported */
		public ?bool $lockedDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesCodeSystem> codeSystem A code system supported by the server */
		public array $codeSystem = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesExpansion expansion Information about the [ValueSet/$expand](valueset-operation-expand.html) operation */
		public ?TerminologyCapabilities\TerminologyCapabilitiesExpansion $expansion = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeSearchSupportType codeSearch explicit | all */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeSearchSupportType $codeSearch = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesValidateCode validateCode Information about the [ValueSet/$validate-code](valueset-operation-validate-code.html) operation */
		public ?TerminologyCapabilities\TerminologyCapabilitiesValidateCode $validateCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesTranslation translation Information about the [ConceptMap/$translate](conceptmap-operation-translate.html) operation */
		public ?TerminologyCapabilities\TerminologyCapabilitiesTranslation $translation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesClosure closure Information about the [ConceptMap/$closure](conceptmap-operation-closure.html) operation */
		public ?TerminologyCapabilities\TerminologyCapabilitiesClosure $closure = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
