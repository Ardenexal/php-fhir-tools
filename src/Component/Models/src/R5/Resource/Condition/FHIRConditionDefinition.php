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
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ConditionDefinition
 *
 * @description A definition of a condition and information relevant to managing it.
 */
#[FhirResource(
    type: 'ConditionDefinition',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ConditionDefinition',
    fhirVersion: 'R5',
)]
class FHIRConditionDefinition extends FHIRDomainResource
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
        /** @var FHIRUri|null url Canonical identifier for this condition definition, represented as a URI (globally unique) */
        public ?FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the condition definition */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the condition definition */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public FHIRString|string|FHIRCoding|null $versionAlgorithmX = null,
        /** @var FHIRString|string|null name Name for this condition definition (computer friendly) */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this condition definition (human friendly) */
        public FHIRString|string|null $title = null,
        /** @var FHIRString|string|null subtitle Subordinate title of the event definition */
        public FHIRString|string|null $subtitle = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?FHIRBoolean $experimental = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher/steward (organization or individual) */
        public FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the condition definition */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for condition definition (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRCodeableConcept|null code Identification of the condition, problem or diagnosis */
        #[NotBlank]
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRCodeableConcept|null severity Subjective severity of condition */
        public ?FHIRCodeableConcept $severity = null,
        /** @var FHIRCodeableConcept|null bodySite Anatomical location, if relevant */
        public ?FHIRCodeableConcept $bodySite = null,
        /** @var FHIRCodeableConcept|null stage Stage/grade, usually assessed formally */
        public ?FHIRCodeableConcept $stage = null,
        /** @var FHIRBoolean|null hasSeverity Whether Severity is appropriate */
        public ?FHIRBoolean $hasSeverity = null,
        /** @var FHIRBoolean|null hasBodySite Whether bodySite is appropriate */
        public ?FHIRBoolean $hasBodySite = null,
        /** @var FHIRBoolean|null hasStage Whether stage is appropriate */
        public ?FHIRBoolean $hasStage = null,
        /** @var array<FHIRUri> definition Formal Definition for the condition */
        public array $definition = [],
        /** @var array<FHIRConditionDefinitionObservation> observation Observations particularly relevant to this condition */
        public array $observation = [],
        /** @var array<FHIRConditionDefinitionMedication> medication Medications particularly relevant for this condition */
        public array $medication = [],
        /** @var array<FHIRConditionDefinitionPrecondition> precondition Observation that suggets this condition */
        public array $precondition = [],
        /** @var array<FHIRReference> team Appropriate team for this condition */
        public array $team = [],
        /** @var array<FHIRConditionDefinitionQuestionnaire> questionnaire Questionnaire for this condition */
        public array $questionnaire = [],
        /** @var array<FHIRConditionDefinitionPlan> plan Plan that is appropriate */
        public array $plan = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
