<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/ActorDefinition
 * @description Describes an actor - a human or an application that plays a role in data exchange, and that may have obligations associated with the role the actor plays.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'ActorDefinition',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/ActorDefinition',
	fhirVersion: 'R5',
)]
class FHIRActorDefinition extends FHIRDomainResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri url Canonical identifier for this actor definition, represented as a URI (globally unique) */
		public ?FHIRUri $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Additional identifier for the actor definition (business identifier) */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string version Business version of the actor definition */
		public FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding versionAlgorithmX How to compare versions */
		public FHIRString|string|FHIRCoding|null $versionAlgorithmX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string name Name for this actor definition (computer friendly) */
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string title Name for this actor definition (human friendly) */
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description Natural language description of the actor */
		public ?FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> jurisdiction Intended jurisdiction for actor definition (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown purpose Why this actor definition is defined */
		public ?FHIRMarkdown $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown copyright Use and/or publishing restrictions */
		public ?FHIRMarkdown $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string copyrightLabel Copyright holder and year(s) */
		public FHIRString|string|null $copyrightLabel = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExampleScenarioActorTypeType type person | system */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRExampleScenarioActorTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown documentation Functionality associated with the actor */
		public ?FHIRMarkdown $documentation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUrl> reference Reference to more information about the actor */
		public array $reference = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical capabilities CapabilityStatement for the actor (if applicable) */
		public ?FHIRCanonical $capabilities = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical> derivedFrom Definition of this actor in another context / IG */
		public array $derivedFrom = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
