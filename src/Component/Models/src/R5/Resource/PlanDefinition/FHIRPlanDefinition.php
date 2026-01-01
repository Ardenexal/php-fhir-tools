<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/PlanDefinition
 *
 * @description This resource allows for the definition of various types of plans as a sharable, consumable, and executable artifact. The resource is general enough to support the description of a broad range of clinical and non-clinical artifacts such as clinical decision support rules, order sets, protocols, and drug quality specifications.
 */
#[FhirResource(
    type: 'PlanDefinition',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/PlanDefinition',
    fhirVersion: 'R5',
)]
class FHIRPlanDefinition extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Canonical identifier for this plan definition, represented as a URI (globally unique) */
        public ?FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the plan definition */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the plan definition */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public FHIRString|string|FHIRCoding|null $versionAlgorithmX = null,
        /** @var FHIRString|string|null name Name for this plan definition (computer friendly) */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this plan definition (human friendly) */
        public FHIRString|string|null $title = null,
        /** @var FHIRString|string|null subtitle Subordinate title of the plan definition */
        public FHIRString|string|null $subtitle = null,
        /** @var FHIRCodeableConcept|null type order-set | clinical-protocol | eca-rule | workflow-definition */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?FHIRBoolean $experimental = null,
        /** @var FHIRCodeableConcept|FHIRReference|FHIRCanonical|null subjectX Type of individual the plan definition is focused on */
        public FHIRCodeableConcept|FHIRReference|FHIRCanonical|null $subjectX = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher/steward (organization or individual) */
        public FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the plan definition */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for plan definition (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this plan definition is defined */
        public ?FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null usage Describes the clinical usage of the plan */
        public ?FHIRMarkdown $usage = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?FHIRMarkdown $copyright = null,
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) */
        public FHIRString|string|null $copyrightLabel = null,
        /** @var FHIRDate|null approvalDate When the plan definition was approved by publisher */
        public ?FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate When the plan definition was last reviewed by the publisher */
        public ?FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod When the plan definition is expected to be used */
        public ?FHIRPeriod $effectivePeriod = null,
        /** @var array<FHIRCodeableConcept> topic E.g. Education, Treatment, Assessment */
        public array $topic = [],
        /** @var array<FHIRContactDetail> author Who authored the content */
        public array $author = [],
        /** @var array<FHIRContactDetail> editor Who edited the content */
        public array $editor = [],
        /** @var array<FHIRContactDetail> reviewer Who reviewed the content */
        public array $reviewer = [],
        /** @var array<FHIRContactDetail> endorser Who endorsed the content */
        public array $endorser = [],
        /** @var array<FHIRRelatedArtifact> relatedArtifact Additional documentation, citations */
        public array $relatedArtifact = [],
        /** @var array<FHIRCanonical> library Logic used by the plan definition */
        public array $library = [],
        /** @var array<FHIRPlanDefinitionGoal> goal What the plan is trying to accomplish */
        public array $goal = [],
        /** @var array<FHIRPlanDefinitionActor> actor Actors within the plan */
        public array $actor = [],
        /** @var array<FHIRPlanDefinitionAction> action Action defined by the plan */
        public array $action = [],
        /** @var FHIRBoolean|FHIRCodeableConcept|null asNeededX Preconditions for service */
        public FHIRBoolean|FHIRCodeableConcept|null $asNeededX = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
