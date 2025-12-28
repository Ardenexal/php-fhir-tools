<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/AllergyIntolerance
 *
 * @description Risk of harmful or undesirable physiological response which is specific to an individual and associated with exposure to a substance.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'AllergyIntolerance',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/AllergyIntolerance',
    fhirVersion: 'R5',
)]
class FHIRAllergyIntolerance extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier External ids for this item */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null clinicalStatus active | inactive | resolved */
        public ?\FHIRCodeableConcept $clinicalStatus = null,
        /** @var FHIRCodeableConcept|null verificationStatus unconfirmed | presumed | confirmed | refuted | entered-in-error */
        public ?\FHIRCodeableConcept $verificationStatus = null,
        /** @var FHIRCodeableConcept|null type allergy | intolerance - Underlying mechanism (if known) */
        public ?\FHIRCodeableConcept $type = null,
        /** @var array<FHIRAllergyIntoleranceCategoryType> category food | medication | environment | biologic */
        public array $category = [],
        /** @var FHIRAllergyIntoleranceCriticalityType|null criticality low | high | unable-to-assess */
        public ?\FHIRAllergyIntoleranceCriticalityType $criticality = null,
        /** @var FHIRCodeableConcept|null code Code that identifies the allergy or intolerance */
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRReference|null patient Who the allergy or intolerance is for */
        #[NotBlank]
        public ?\FHIRReference $patient = null,
        /** @var FHIRReference|null encounter Encounter when the allergy or intolerance was asserted */
        public ?\FHIRReference $encounter = null,
        /** @var FHIRDateTime|FHIRAge|FHIRPeriod|FHIRRange|FHIRString|string|null onsetX When allergy or intolerance was identified */
        public \FHIRDateTime|\FHIRAge|\FHIRPeriod|\FHIRRange|\FHIRString|string|null $onsetX = null,
        /** @var FHIRDateTime|null recordedDate Date allergy or intolerance was first recorded */
        public ?\FHIRDateTime $recordedDate = null,
        /** @var array<FHIRAllergyIntoleranceParticipant> participant Who or what participated in the activities related to the allergy or intolerance and how they were involved */
        public array $participant = [],
        /** @var FHIRDateTime|null lastOccurrence Date(/time) of last known occurrence of a reaction */
        public ?\FHIRDateTime $lastOccurrence = null,
        /** @var array<FHIRAnnotation> note Additional text not captured in other fields */
        public array $note = [],
        /** @var array<FHIRAllergyIntoleranceReaction> reaction Adverse Reaction Events linked to exposure to substance */
        public array $reaction = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
