<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DetectedIssue
 *
 * @description Indicates an actual or potential clinical issue with or between one or more active or proposed clinical actions for a patient; e.g. Drug-drug interaction, Ineffective treatment frequency, Procedure-condition conflict, gaps in care, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'DetectedIssue', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/DetectedIssue', fhirVersion: 'R5')]
class FHIRDetectedIssue extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Unique id for the detected issue */
        public array $identifier = [],
        /** @var FHIRDetectedIssueStatusType|null status preliminary | final | entered-in-error | mitigated */
        #[NotBlank]
        public ?\FHIRDetectedIssueStatusType $status = null,
        /** @var array<FHIRCodeableConcept> category Type of detected issue, e.g. drug-drug, duplicate therapy, etc */
        public array $category = [],
        /** @var FHIRCodeableConcept|null code Specific type of detected issue, e.g. drug-drug, duplicate therapy, etc */
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRDetectedIssueSeverityType|null severity high | moderate | low */
        public ?\FHIRDetectedIssueSeverityType $severity = null,
        /** @var FHIRReference|null subject Associated subject */
        public ?\FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter detected issue is part of */
        public ?\FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRPeriod|null identifiedX When identified */
        public \FHIRDateTime|\FHIRPeriod|null $identifiedX = null,
        /** @var FHIRReference|null author The provider or device that identified the issue */
        public ?\FHIRReference $author = null,
        /** @var array<FHIRReference> implicated Problem resource */
        public array $implicated = [],
        /** @var array<FHIRDetectedIssueEvidence> evidence Supporting evidence */
        public array $evidence = [],
        /** @var FHIRMarkdown|null detail Description and context */
        public ?\FHIRMarkdown $detail = null,
        /** @var FHIRUri|null reference Authority for issue */
        public ?\FHIRUri $reference = null,
        /** @var array<FHIRDetectedIssueMitigation> mitigation Step taken to address */
        public array $mitigation = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
