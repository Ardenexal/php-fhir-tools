<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Condition
 *
 * @description A clinical condition, problem, diagnosis, or other event, situation, issue, or clinical concept that has risen to a level of concern.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Condition', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Condition', fhirVersion: 'R4')]
class FHIRCondition extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier External Ids for this condition */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null clinicalStatus active | recurrence | relapse | inactive | remission | resolved */
        public ?\FHIRCodeableConcept $clinicalStatus = null,
        /** @var FHIRCodeableConcept|null verificationStatus unconfirmed | provisional | differential | confirmed | refuted | entered-in-error */
        public ?\FHIRCodeableConcept $verificationStatus = null,
        /** @var array<FHIRCodeableConcept> category problem-list-item | encounter-diagnosis */
        public array $category = [],
        /** @var FHIRCodeableConcept|null severity Subjective severity of condition */
        public ?\FHIRCodeableConcept $severity = null,
        /** @var FHIRCodeableConcept|null code Identification of the condition, problem or diagnosis */
        public ?\FHIRCodeableConcept $code = null,
        /** @var array<FHIRCodeableConcept> bodySite Anatomical location, if relevant */
        public array $bodySite = [],
        /** @var FHIRReference|null subject Who has the condition? */
        #[NotBlank]
        public ?\FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter created as part of */
        public ?\FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRAge|FHIRPeriod|FHIRRange|FHIRString|string|null onsetX Estimated or actual date,  date-time, or age */
        public \FHIRDateTime|\FHIRAge|\FHIRPeriod|\FHIRRange|\FHIRString|string|null $onsetX = null,
        /** @var FHIRDateTime|FHIRAge|FHIRPeriod|FHIRRange|FHIRString|string|null abatementX When in resolution/remission */
        public \FHIRDateTime|\FHIRAge|\FHIRPeriod|\FHIRRange|\FHIRString|string|null $abatementX = null,
        /** @var FHIRDateTime|null recordedDate Date record was first recorded */
        public ?\FHIRDateTime $recordedDate = null,
        /** @var FHIRReference|null recorder Who recorded the condition */
        public ?\FHIRReference $recorder = null,
        /** @var FHIRReference|null asserter Person who asserts this condition */
        public ?\FHIRReference $asserter = null,
        /** @var array<FHIRConditionStage> stage Stage/grade, usually assessed formally */
        public array $stage = [],
        /** @var array<FHIRConditionEvidence> evidence Supporting evidence */
        public array $evidence = [],
        /** @var array<FHIRAnnotation> note Additional information about the Condition */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
