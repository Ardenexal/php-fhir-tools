<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ActivityDefinition
 *
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
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Canonical identifier for this activity definition, represented as a URI (globally unique) */
        public ?\FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the activity definition */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the activity definition */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|null name Name for this activity definition (computer friendly) */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this activity definition (human friendly) */
        public \FHIRString|string|null $title = null,
        /** @var FHIRString|string|null subtitle Subordinate title of the activity definition */
        public \FHIRString|string|null $subtitle = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?\FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?\FHIRBoolean $experimental = null,
        /** @var FHIRCodeableConcept|FHIRReference|FHIRCanonical|null subjectX Type of individual the activity definition is intended for */
        public \FHIRCodeableConcept|\FHIRReference|\FHIRCanonical|null $subjectX = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher (organization or individual) */
        public \FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the activity definition */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for activity definition (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this activity definition is defined */
        public ?\FHIRMarkdown $purpose = null,
        /** @var FHIRString|string|null usage Describes the clinical usage of the activity definition */
        public \FHIRString|string|null $usage = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?\FHIRMarkdown $copyright = null,
        /** @var FHIRDate|null approvalDate When the activity definition was approved by publisher */
        public ?\FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate When the activity definition was last reviewed */
        public ?\FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod When the activity definition is expected to be used */
        public ?\FHIRPeriod $effectivePeriod = null,
        /** @var array<FHIRCodeableConcept> topic E.g. Education, Treatment, Assessment, etc. */
        public array $topic = [],
        /** @var array<FHIRContactDetail> author Who authored the content */
        public array $author = [],
        /** @var array<FHIRContactDetail> editor Who edited the content */
        public array $editor = [],
        /** @var array<FHIRContactDetail> reviewer Who reviewed the content */
        public array $reviewer = [],
        /** @var array<FHIRContactDetail> endorser Who endorsed the content */
        public array $endorser = [],
        /** @var array<FHIRRelatedArtifact> relatedArtifact Additional documentation, citations, etc. */
        public array $relatedArtifact = [],
        /** @var array<FHIRCanonical> library Logic used by the activity definition */
        public array $library = [],
        /** @var FHIRRequestResourceTypeType|null kind Kind of resource */
        public ?\FHIRRequestResourceTypeType $kind = null,
        /** @var FHIRCanonical|null profile What profile the resource needs to conform to */
        public ?\FHIRCanonical $profile = null,
        /** @var FHIRCodeableConcept|null code Detail type of activity */
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRRequestIntentType|null intent proposal | plan | directive | order | original-order | reflex-order | filler-order | instance-order | option */
        public ?\FHIRRequestIntentType $intent = null,
        /** @var FHIRRequestPriorityType|null priority routine | urgent | asap | stat */
        public ?\FHIRRequestPriorityType $priority = null,
        /** @var FHIRBoolean|null doNotPerform True if the activity should not be performed */
        public ?\FHIRBoolean $doNotPerform = null,
        /** @var FHIRTiming|FHIRDateTime|FHIRAge|FHIRPeriod|FHIRRange|FHIRDuration|null timingX When activity is to occur */
        public \FHIRTiming|\FHIRDateTime|\FHIRAge|\FHIRPeriod|\FHIRRange|\FHIRDuration|null $timingX = null,
        /** @var FHIRReference|null location Where it should happen */
        public ?\FHIRReference $location = null,
        /** @var array<FHIRActivityDefinitionParticipant> participant Who should participate in the action */
        public array $participant = [],
        /** @var FHIRReference|FHIRCodeableConcept|null productX What's administered/supplied */
        public \FHIRReference|\FHIRCodeableConcept|null $productX = null,
        /** @var FHIRQuantity|null quantity How much is administered/consumed/supplied */
        public ?\FHIRQuantity $quantity = null,
        /** @var array<FHIRDosage> dosage Detailed dosage instructions */
        public array $dosage = [],
        /** @var array<FHIRCodeableConcept> bodySite What part of body to perform on */
        public array $bodySite = [],
        /** @var array<FHIRReference> specimenRequirement What specimens are required to perform this action */
        public array $specimenRequirement = [],
        /** @var array<FHIRReference> observationRequirement What observations are required to perform this action */
        public array $observationRequirement = [],
        /** @var array<FHIRReference> observationResultRequirement What observations must be produced by this action */
        public array $observationResultRequirement = [],
        /** @var FHIRCanonical|null transform Transform to apply the template */
        public ?\FHIRCanonical $transform = null,
        /** @var array<FHIRActivityDefinitionDynamicValue> dynamicValue Dynamic aspects of the definition */
        public array $dynamicValue = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
