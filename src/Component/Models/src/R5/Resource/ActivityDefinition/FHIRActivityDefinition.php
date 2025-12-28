<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 * @see http://hl7.org/fhir/StructureDefinition/ActivityDefinition
 * @description This resource allows for the definition of some activity to be performed, independent of a particular patient, practitioner, or other performance context.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'ActivityDefinition',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/ActivityDefinition',
	fhirVersion: 'R5',
)]
class FHIRActivityDefinition extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType language Language of the resource content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri url Canonical identifier for this activity definition, represented as a URI (globally unique) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Additional identifier for the activity definition */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string version Business version of the activity definition */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding versionAlgorithmX How to compare versions */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding|null $versionAlgorithmX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string name Name for this activity definition (computer friendly) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string title Name for this activity definition (human friendly) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string subtitle Subordinate title of the activity definition */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $subtitle = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean experimental For testing purposes, not real usage */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $experimental = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical subjectX Type of individual the activity definition is intended for */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical|null $subjectX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime date Date last changed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string publisher Name of the publisher/steward (organization or individual) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown description Natural language description of the activity definition */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> jurisdiction Intended jurisdiction for activity definition (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown purpose Why this activity definition is defined */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown usage Describes the clinical usage of the activity definition */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $usage = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown copyright Use and/or publishing restrictions */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string copyrightLabel Copyright holder and year(s) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $copyrightLabel = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate approvalDate When the activity definition was approved by publisher */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate $approvalDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate lastReviewDate When the activity definition was last reviewed by the publisher */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate $lastReviewDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod effectivePeriod When the activity definition is expected to be used */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod $effectivePeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> topic E.g. Education, Treatment, Assessment, etc */
		public array $topic = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail> author Who authored the content */
		public array $author = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail> editor Who edited the content */
		public array $editor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail> reviewer Who reviewed the content */
		public array $reviewer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail> endorser Who endorsed the content */
		public array $endorser = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact> relatedArtifact Additional documentation, citations, etc */
		public array $relatedArtifact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical> library Logic used by the activity definition */
		public array $library = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestResourceTypesType kind Kind of resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestResourceTypesType $kind = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical profile What profile the resource needs to conform to */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $profile = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept code Detail type of activity */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestIntentType intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestIntentType $intent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestPriorityType priority routine | urgent | asap | stat */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRequestPriorityType $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean doNotPerform True if the activity should not be performed */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $doNotPerform = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration timingX When activity is to occur */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration|null $timingX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept asNeededX Preconditions for service */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|null $asNeededX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference location Where it should happen */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActivityDefinitionParticipant> participant Who should participate in the action */
		public array $participant = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept productX What's administered/supplied */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|null $productX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity quantity How much is administered/consumed/supplied */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $quantity = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDosage> dosage Detailed dosage instructions */
		public array $dosage = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> bodySite What part of body to perform on */
		public array $bodySite = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical> specimenRequirement What specimens are required to perform this action */
		public array $specimenRequirement = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical> observationRequirement What observations are required to perform this action */
		public array $observationRequirement = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical> observationResultRequirement What observations must be produced by this action */
		public array $observationResultRequirement = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical transform Transform to apply the template */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $transform = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRActivityDefinitionDynamicValue> dynamicValue Dynamic aspects of the definition */
		public array $dynamicValue = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
