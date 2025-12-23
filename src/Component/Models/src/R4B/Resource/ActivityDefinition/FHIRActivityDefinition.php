<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 * @see http://hl7.org/fhir/StructureDefinition/ActivityDefinition
 * @description This resource allows for the definition of some activity to be performed, independent of a particular patient, practitioner, or other performance context.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'ActivityDefinition',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/ActivityDefinition',
	fhirVersion: 'R4B',
)]
class FHIRActivityDefinition extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri url Canonical identifier for this activity definition, represented as a URI (globally unique) */
		public ?FHIRUri $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier Additional identifier for the activity definition */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string version Business version of the activity definition */
		public FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string name Name for this activity definition (computer friendly) */
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string title Name for this activity definition (human friendly) */
		public FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string subtitle Subordinate title of the activity definition */
		public FHIRString|string|null $subtitle = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRPublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean experimental For testing purposes, not real usage */
		public ?FHIRBoolean $experimental = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical subjectX Type of individual the activity definition is intended for */
		public FHIRCodeableConcept|FHIRReference|FHIRCanonical|null $subjectX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime date Date last changed */
		public ?FHIRDateTime $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string publisher Name of the publisher (organization or individual) */
		public FHIRString|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown description Natural language description of the activity definition */
		public ?FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> jurisdiction Intended jurisdiction for activity definition (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown purpose Why this activity definition is defined */
		public ?FHIRMarkdown $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string usage Describes the clinical usage of the activity definition */
		public FHIRString|string|null $usage = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown copyright Use and/or publishing restrictions */
		public ?FHIRMarkdown $copyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate approvalDate When the activity definition was approved by publisher */
		public ?FHIRDate $approvalDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDate lastReviewDate When the activity definition was last reviewed */
		public ?FHIRDate $lastReviewDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod effectivePeriod When the activity definition is expected to be used */
		public ?FHIRPeriod $effectivePeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> topic E.g. Education, Treatment, Assessment, etc. */
		public array $topic = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> author Who authored the content */
		public array $author = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> editor Who edited the content */
		public array $editor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> reviewer Who reviewed the content */
		public array $reviewer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactDetail> endorser Who endorsed the content */
		public array $endorser = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRelatedArtifact> relatedArtifact Additional documentation, citations, etc. */
		public array $relatedArtifact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical> library Logic used by the activity definition */
		public array $library = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRequestResourceTypeType kind Kind of resource */
		public ?FHIRRequestResourceTypeType $kind = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical profile What profile the resource needs to conform to */
		public ?FHIRCanonical $profile = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept code Detail type of activity */
		public ?FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRequestIntentType intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
		public ?FHIRRequestIntentType $intent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRequestPriorityType priority routine | urgent | asap | stat */
		public ?FHIRRequestPriorityType $priority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean doNotPerform True if the activity should not be performed */
		public ?FHIRBoolean $doNotPerform = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTiming|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAge|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDuration timingX When activity is to occur */
		public FHIRTiming|FHIRDateTime|FHIRAge|FHIRPeriod|FHIRRange|FHIRDuration|null $timingX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference location Where it should happen */
		public ?FHIRReference $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRActivityDefinitionParticipant> participant Who should participate in the action */
		public array $participant = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept productX What's administered/supplied */
		public FHIRReference|FHIRCodeableConcept|null $productX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity quantity How much is administered/consumed/supplied */
		public ?FHIRQuantity $quantity = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDosage> dosage Detailed dosage instructions */
		public array $dosage = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> bodySite What part of body to perform on */
		public array $bodySite = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> specimenRequirement What specimens are required to perform this action */
		public array $specimenRequirement = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> observationRequirement What observations are required to perform this action */
		public array $observationRequirement = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> observationResultRequirement What observations must be produced by this action */
		public array $observationResultRequirement = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical transform Transform to apply the template */
		public ?FHIRCanonical $transform = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRActivityDefinitionDynamicValue> dynamicValue Dynamic aspects of the definition */
		public array $dynamicValue = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
